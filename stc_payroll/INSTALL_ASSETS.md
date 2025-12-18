# Install AdminLTE Assets

The CSS and JavaScript files are not loading because the AdminLTE assets need to be copied to the public folder.

## Quick Setup

You need to copy the AdminLTE assets from the superadmin project:

### Option 1: Copy Folders (Recommended)

1. Copy the `dist` folder:
   - From: `E:\xampp\htdocs\stc\superadmin.stcassociate.com\public\dist`
   - To: `E:\xampp\htdocs\stc\stc_payroll\public\dist`

2. Copy the `plugins` folder:
   - From: `E:\xampp\htdocs\stc\superadmin.stcassociate.com\public\plugins`
   - To: `E:\xampp\htdocs\stc\stc_payroll\public\plugins`

### Option 2: Use PowerShell (Run as Administrator)

```powershell
cd E:\xampp\htdocs\stc\stc_payroll
Copy-Item -Path "..\superadmin.stcassociate.com\public\dist" -Destination "public\dist" -Recurse -Force
Copy-Item -Path "..\superadmin.stcassociate.com\public\plugins" -Destination "public\plugins" -Recurse -Force
```

### Option 3: Create Symbolic Links (Requires Admin)

```powershell
# Run PowerShell as Administrator
cd E:\xampp\htdocs\stc\stc_payroll
New-Item -ItemType SymbolicLink -Path "public\dist" -Target "E:\xampp\htdocs\stc\superadmin.stcassociate.com\public\dist"
New-Item -ItemType SymbolicLink -Path "public\plugins" -Target "E:\xampp\htdocs\stc\superadmin.stcassociate.com\public\plugins"
```

After copying the assets, refresh your browser at `http://localhost/stc/stc_payroll/` and the CSS/JS should load correctly.

