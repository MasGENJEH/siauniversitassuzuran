# Script for splitting frontend and backend
# Run this script from the project root

# 1. Create frontend directory structure
Write-Host "Creating frontend directory..."
New-Item -ItemType Directory -Force -Path "frontend"
New-Item -ItemType Directory -Force -Path "frontend/src"
New-Item -ItemType Directory -Force -Path "frontend/src/assets"

# 2. Move JS and CSS files
Write-Host "Moving React components to frontend/src..."
Move-Item -Path "resources/js/*" -Destination "frontend/src/" -Force
Move-Item -Path "resources/css/app.css" -Destination "frontend/src/assets/index.css" -Force

# 3. Rename app.jsx to main.jsx
Rename-Item -Path "frontend/src/app.jsx" -NewName "main.jsx"

# 4. Clean up old resources directories
Remove-Item -Path "resources/js" -Recurse -Force
Remove-Item -Path "resources/css" -Recurse -Force

# 5. Clean up Laravel root package files
Write-Host "Cleaning up root package files..."
Remove-Item -Path "package.json" -Force
Remove-Item -Path "package-lock.json" -Force
Remove-Item -Path "vite.config.js" -Force
if (Test-Path "node_modules") {
    Remove-Item -Path "node_modules" -Recurse -Force
}

Write-Host "Pemisahan selesai! Silakan jalankan 'cd frontend' dan 'npm install'."
