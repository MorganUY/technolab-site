function validateRegistration() {
    // Получаем элементы формы
    var uid = document.forms["regForm"]["reg_login"];
    var passid = document.forms["regForm"]["reg_password"];
    var uemail = document.forms["regForm"]["email"];
    var uzip = document.forms["regForm"]["zip"];

    //Проверка логина (длина от 5 до 12)
    if (!userid_validation(uid, 5, 12)) return false;

    //Проверка пароля (длина от 7 до 12)
    if (!passid_validation(passid, 7, 12)) return false;

    //Проверка Email (новое поле)
    if (!ValidateEmail(uemail)) return false;

    //Проверка индекса (новое поле, только цифры)
    if (!allnumeric(uzip)) return false;

    alert('Форма заполнена верно! Отправляем на сервер...');
    return true;
}


function userid_validation(uid, mx, my) {
    var uid_len = uid.value.length;
    if (uid_len == 0 || uid_len >= my || uid_len < mx) {
        alert("Логин не должен быть пустым. Длина: " + mx + "-" + my);
        uid.focus();
        return false;
    }
    return true;
}

function passid_validation(passid, mx, my) {
    var passid_len = passid.value.length;
    if (passid_len == 0 || passid_len >= my || passid_len < mx) {
        alert("Пароль слишком короткий или длинный (" + mx + "-" + my + " симв.)");
        passid.focus();
        return false;
    }
    return true;
}

function ValidateEmail(uemail) {
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (uemail.value.match(mailformat)) {
        return true;
    } else {
        alert("Вы ввели неверный адрес электронной почты!");
        uemail.focus();
        return false;
    }
}

function allnumeric(uzip) {
    var numbers = /^[0-9]+$/;
    if (uzip.value.match(numbers)) {
        return true;
    } else {
        alert('Индекс должен содержать только цифры!');
        uzip.focus();
        return false;
    }
}