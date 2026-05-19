#requires -Version 5.1
<#
.SYNOPSIS
  Build STC GLD locally, zip build/, commit, and push. GitHub deploy workflow expands the zip on the server.

.DESCRIPTION
  1. If there are git changes under stc_gld/ (or use -Force), runs npm run build in stc_gld.
  2. Creates stc_gld/build-archive.zip (contains build/ folder).
  3. git add . at repo root, prompts for commit message, commit, push.

  Run from repo root:  .\deploy-stc-gld.ps1
  Force rebuild:        .\deploy-stc-gld.ps1 -Force

.NOTES
  stc_gld/build is gitignored; commit the zip so .github/workflows/deploy.yml can unzip on the server.
#>
param(
    [switch]$Force
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

$statusLines = @(git -C $repoRoot status --porcelain $stcGldRel 2>$null)
if ($statusLines.Count -eq 0 -and -not $Force) {
    Write-Host "No changes under $stcGldRel. Use -Force to run build, refresh build-archive.zip, commit, and push anyway."
    exit 0
}

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

Write-Host "Done. Remote deploy workflow will unzip build-archive.zip on the server."
