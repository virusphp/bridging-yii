# Bridging YII 1 Modifikasi Component BPJS
---
##### Tools :
- Git
- Composer
- Text Editor (Nodepad++,Sublime Text, VSCode, Vim, etc)
- PHP v.7.x

##### Pull/Menarik Repositori :
- `git clone https://github.com/virusphp/bridging-yii.git`

##### Buat Branch :
- agar tidak mengganggu branch utama `master` lebih baik jika membuat branch sendiri untuk melakukan pengeditan sebelum di `push` ke branch utama `master`
- contoh pembuatan Branch sesuai dengan pengerjaan yang dilakukan dengan format nama_pekerjaan contoh aman_finance / aman_fin / fin_aman / finance_aman
- untuk sintak pembuatan branch seperti di bawah
- `git branch <nama-branch-baru>`
- `git checkout <nama-branch-baru>`

##### Push/Upload:
- `git add .` atau `git add -A`
- `git commit -m "<pesan>"` contoh `git commit -m "bagusin header"`
- `git push` atau `git push origin <nama-branch>`

##### Pull Request :
- jika yang dikerjakan di branch sudah stabil atau tidak ada bug, bisa dilakukan <b>Pull Request</b> dari branch yang baru ke branch utama `master`
- `git add .`
- `git commit -m "<pesan>"`
- `git checkout master`
- `git pull origin master`
- Buka halaman `https://github.com/virusphp/bridging-yii.git` dan klik `membuat pull request`

##### Penggunaan / installasi :
- `composer install --verbose`
- `composer du`
- run service `php -S localhost:8000 -t testdrive/`

---
MegonoDev Team