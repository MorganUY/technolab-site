// Данные для слайдера: если есть phpSliderData из PHP - используем его, иначе - резервные данные
const sliderData = (typeof phpSliderData !== 'undefined') ? phpSliderData : [
    {
        title: 'ТехноЛаб',
        text: 'Профессиональный ремонт цифровой техники любой сложности.',
        img: 'images/photo1.jpg' 
    },
    {
        title: 'Сборка <span class="highlight">ПК</span>',
        text: 'Индивидуальный подбор комплектующих под ваш бюджет.',
        img: 'images/2.jpg' 
    },
    {
        title: 'Настройка <span class="highlight">ПО</span>',
        text: 'Установка ОС и драйверов под ключ.',
        img: 'images/photo2.jpg' 
    }
];

// Находим элементы на странице для управления слайдером
const titleElem = document.querySelector('.slider-title');
const textElem = document.getElementById('sliderText');
const imgElem = document.getElementById('mainImage');
const dots = document.querySelectorAll('.dot');
const prevBtn = document.querySelector('.slider-btn--prev');
const nextBtn = document.querySelector('.slider-btn--next');

let currentSlide = 0;

// Функция переключения слайда по индексу
function showSlide(index) {
    // Проверяем, существует ли слайд с таким индексом
    if (!sliderData[index]) return;
    
    currentSlide = index;

    // Скрываем текущее изображение (анимация исчезновения)
    if (imgElem) imgElem.style.opacity = '0';
    
    setTimeout(() => {
        // Обновляем активную точку (индикатор слайда)
        dots.forEach(d => d.classList.remove('active'));
        if (dots[index]) dots[index].classList.add('active');

        // Обновляем текст и заголовок
        if (titleElem) titleElem.innerHTML = sliderData[index].title;
        if (textElem) textElem.textContent = sliderData[index].text;
        
        // Обновляем картинку и показываем её
        if (imgElem) {
            imgElem.src = sliderData[index].img;
            imgElem.style.opacity = '1';
        }
    }, 400);
}

// Обработчики кнопок навигации
if (prevBtn) {
    prevBtn.addEventListener('click', () => {
        const newIndex = currentSlide > 0 ? currentSlide - 1 : sliderData.length - 1;
        showSlide(newIndex);
    });
}

if (nextBtn) {
    nextBtn.addEventListener('click', () => {
        const newIndex = currentSlide < sliderData.length - 1 ? currentSlide + 1 : 0;
        showSlide(newIndex);
    });
}

// Вешаем обработчик клика на точки (индикаторы слайдов)
dots.forEach((dot) => {
    dot.addEventListener('click', (e) => {
        // Получаем индекс из data-index атрибута
        const index = parseInt(e.target.dataset.index);
        if (!isNaN(index)) {
            showSlide(index);
        }
    });
});

// Инициализация слайдера при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    showSlide(0);
});


// ЭТО часть 10-11
function validateForm() {
    var phone = document.forms["regForm"]["phone"].value;
    if (phone == "") {
        alert("Поле телефон должно быть заполнено");
        return false;
    }
    // Пример из методички: проверка на латинские буквы и цифры для адреса
    var address = document.forms["regForm"]["address"].value;
    var letters = /^[0-9a-zA-Z\s]+$/;
    if(!address.match(letters)) {
        alert('Адрес должен содержать только латинские буквы и цифры');
        return false;
    }
}

showSlide(0); // Это принудительно запустит первый слайд сразу