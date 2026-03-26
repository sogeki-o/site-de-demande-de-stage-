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

Write-Host "[5/7] Verification de la configuration MySQL..." -ForegroundColor Cyan
$envContent = Get-Content ".env" -Raw

if ($envContent -notmatch "(?m)^DB_CONNECTION=mysql\s*$") {
    Write-Host "DB_CONNECTION doit etre defini a mysql dans .env." -ForegroundColor Red
    exit 1
}

$requiredVars = @("127.0.0.1", "3306", "gestion_stages_uca", "root")
foreach ($varName in $requiredVars) {
    if ($envContent -notmatch "(?m)^$varName=.+$") {
        Write-Host "$varName est vide ou absent dans .env." -ForegroundColor Red
        exit 1
    }
}

Write-Host "[6/7] Migration et seeding de la base..." -ForegroundColor Cyan
php artisan migrate --seed

Write-Host "[7/7] Nettoyage du cache applicatif..." -ForegroundColor Cyan
php artisan optimize:clear

Write-Host "Configuration terminee. Lance ensuite: php artisan serve et npm run dev" -ForegroundColor Green
