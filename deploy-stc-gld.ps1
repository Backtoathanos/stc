#requires -Version 5.1
<#
.SYNOPSIS
  Deploy STC GLD: optionally rebuild React, commit, and push. CI deploys via git pull on the server.

.DESCRIPTION
  - Rebuilds React + refreshes build-archive.zip only when stc_gld source files changed
    (excludes build/, build-archive.zip, node_modules/).
  - -Force: commit and push even when stc_gld has no changes, without rebuilding React.
  - -Rebuild: always run npm run build and refresh build-archive.zip.

  Run from repo root:  .\deploy-stc-gld.ps1
  Deploy without build: .\deploy-stc-gld.ps1 -Force
  Force rebuild:        .\deploy-stc-gld.ps1 -Rebuild

.NOTES
  stc_gld/build is gitignored; build-archive.zip is committed when React is rebuilt.
  GitHub deploy workflow only unzips when build-archive.zip changed in the pulled commits.
#>
param(
    [switch]$Force,
    [switch]$Rebuild
)

$ErrorActionPreference = 'Stop'
$repoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $repoRoot

$stcGldRel = "stc_gld"
$stcGld = Join-Path $repoRoot $stcGldRel

if (-not (Test-Path $stcGld)) {
    Write-Error "Expected folder not found: $stcGld"
    exit 1
}

function Test-StcGldSourceChanged {
    param([string]$Root, [string]$RelPath)

    $lines = @(git -C $Root status --porcelain $RelPath 2>$null)
    foreach ($line in $lines) {
        if ($line.Length -lt 4) { continue }
        $path = $line.Substring(3).Trim('"')
        if ($path -match '(^|/)(build|node_modules)(/|$)') { continue }
        if ($path -match 'build-archive\.zip$') { continue }
        return $true
    }
    return $false
}

$allChanges = @(git -C $repoRoot status --porcelain 2>$null)
$sourceChanged = Test-StcGldSourceChanged -Root $repoRoot -RelPath $stcGldRel
$shouldBuild = $Rebuild -or $sourceChanged

if ($allChanges.Count -eq 0 -and -not $Force) {
    Write-Host "No changes in the repo. Use -Force to commit and push anyway (skips React build)."
    exit 0
}

if (-not $shouldBuild) {
    if ($Force) {
        Write-Host "Force deploy: no React source changes — skipping npm run build and build-archive.zip."
    } else {
        Write-Host "No React source changes under $stcGldRel — skipping npm run build and build-archive.zip."
    }
} else {
    Push-Location $stcGld
    try {
        Write-Host "Running npm run build..."
        npm run build
        if ($LASTEXITCODE -ne 0) {
            Write-Error "npm run build failed with exit code $LASTEXITCODE"
            exit $LASTEXITCODE
        }
    } finally {
        Pop-Location
    }

    $buildDir = Join-Path $stcGld "build"
    if (-not (Test-Path $buildDir)) {
        Write-Error "Build folder missing: $buildDir"
        exit 1
    }

    $zipPath = Join-Path $stcGld "build-archive.zip"
    if (Test-Path $zipPath) {
        Remove-Item $zipPath -Force
    }
    Write-Host "Creating $zipPath ..."
    Compress-Archive -LiteralPath $buildDir -DestinationPath $zipPath -CompressionLevel Optimal -Force
}

Set-Location $repoRoot
git add .

git diff --cached --quiet 2>$null
if ($LASTEXITCODE -eq 0) {
    Write-Host "Nothing new to commit after git add (no staged diff). Exiting."
    exit 0
}

$msg = Read-Host -Prompt "Commit message"
if ([string]::IsNullOrWhiteSpace($msg)) {
    Write-Error "Commit message is required."
    exit 1
}

git commit -m $msg
if ($LASTEXITCODE -ne 0) {
    Write-Error "git commit failed."
    exit $LASTEXITCODE
}

git push
if ($LASTEXITCODE -ne 0) {
    Write-Error "git push failed."
    exit $LASTEXITCODE
}

if ($shouldBuild) {
    Write-Host "Done. Remote deploy workflow will pull and unzip build-archive.zip on the server."
} else {
    Write-Host "Done. Remote deploy workflow will pull only (no React archive update)."
}
