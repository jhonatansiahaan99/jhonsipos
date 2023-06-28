function progress() {
    let progress = document.querySelector('.progress');
    let step = 16;
    let loading = setInterval(move, 50);//setiap 50 sampai terisi full maka akan diarahkan ke fungsi move

    function move() {// untuk pergerakkan progress bar nya
        if (step == 400) {//jika step sudah capai 400 maka dibersihkan waktu nya dan di arahkan ke login
            clearInterval(loading);
            document.location = "auth/login.php";
        } else {
            step += 4;
            progress.style.width = step + 'px';
        }
    }

}

progress();