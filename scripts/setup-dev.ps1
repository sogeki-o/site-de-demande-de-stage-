$ErrorActionPreference = "Stop"

Write-Host "[1/7] Installation des dependances PHP..." -ForegroundColor Cyan
composer install

Write-Host "[2/7] Installation des dependances JS..." -ForegroundColor Cyan
npm install

if (-not (Test-Path ".env")) {
    Write-Host "[3/7] Creation du fichier .env..." -ForegroundColor Cyan
    Copy-Item ".env.example" ".env"
} else {
    Write-Host "[3/7] .env existe deja, etape ignoree." -ForegroundColor Yellow
}

# APP_KEY can be empty after copying .env.example.
$envContent = Get-Content ".env" -Raw
if ($envContent -match "APP_KEY=\s*$") {
    Write-Host "[4/7] Generation de APP_KEY..." -ForegroundColor Cyan
    php artisan key:generate
} else {
    Write-Host "[4/7] APP_KEY existe deja, etape ignoree." -ForegroundColor Yellow
}

if (-not (Test-Path "database")) {
    New-Item -ItemType Directory -Path "database" | Out-Null
}

if (-not (Test-Path "database/database.mysql")) {
    Write-Host "[5/7] Creation de database/database.mysql..." -ForegroundColor Cyan
    New-Item -ItemType File -Path "database/database.mysql" | Out-Null
} else {
    Write-Host "[5/7] database/database.mysql existe deja, etape ignoree." -ForegroundColor Yellow
}

Write-Host "[6/7] Migration et seeding de la base..." -ForegroundColor Cyan
php artisan migrate --seed

Write-Host "[7/7] Nettoyage du cache applicatif..." -ForegroundColor Cyan
php artisan optimize:clear

Write-Host "Configuration terminee. Lance ensuite: php artisan serve et npm run dev" -ForegroundColor Green
