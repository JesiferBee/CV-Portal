Add-Type -AssemblyName System.Drawing

$assetsDir = Join-Path $PSScriptRoot 'assets\images'
if (-not (Test-Path $assetsDir)) { New-Item -ItemType Directory -Path $assetsDir | Out-Null }

# default-profile.png
$img = New-Object System.Drawing.Bitmap 512,512
$g = [System.Drawing.Graphics]::FromImage($img)
$g.Clear([System.Drawing.Color]::FromArgb(248,251,255))
$brushBlue = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::FromArgb(63,114,175))
$brushWhite = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::White)
$g.FillRectangle($brushBlue, 64,64,384,384)
$g.FillEllipse($brushWhite, 128,120,256,256)
$g.FillEllipse($brushBlue, 192,170,128,128)
$font = New-Object System.Drawing.Font('Arial', 96, [System.Drawing.FontStyle]::Bold)
$size = $g.MeasureString('CV', $font)
$g.DrawString('CV', $font, $brushBlue, (512 - $size.Width) / 2, 360)
$img.Save((Join-Path $assetsDir 'default-profile.png'), [System.Drawing.Imaging.ImageFormat]::Png)
$g.Dispose()
$img.Dispose()

# donate-qr.png
$img = New-Object System.Drawing.Bitmap 512,512
$g = [System.Drawing.Graphics]::FromImage($img)
$g.Clear([System.Drawing.Color]::White)
$black = New-Object System.Drawing.SolidBrush([System.Drawing.Color]::Black)
$block = 48
$offset = 32
for ($y = 0; $y -lt 8; $y++) {
    for ($x = 0; $x -lt 8; $x++) {
        if ((($x + $y) % 2) -eq 0) {
            $g.FillRectangle($black, $offset + $x * 56, $offset + $y * 56, $block, $block)
        }
    }
}
$finder = @( @{x=32;y=32}, @{x=336;y=32}, @{x=32;y=336} )
foreach ($pos in $finder) {
    $g.FillRectangle($black, $pos.x, $pos.y, 144, 144)
    $g.FillRectangle($brushWhite, $pos.x + 24, $pos.y + 24, 96, 96)
    $g.FillRectangle($black, $pos.x + 48, $pos.y + 48, 48, 48)
}
$font2 = New-Object System.Drawing.Font('Arial', 24, [System.Drawing.FontStyle]::Bold)
$text = 'SCAN TO DONATE'
$size = $g.MeasureString($text, $font2)
$g.DrawString($text, $font2, $black, (512 - $size.Width) / 2, 464)
$img.Save((Join-Path $assetsDir 'donate-qr.png'), [System.Drawing.Imaging.ImageFormat]::Png)
$g.Dispose()
$img.Dispose()

Write-Output 'PNG assets created: default-profile.png and donate-qr.png'