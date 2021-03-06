//Var for slideshow
var slideIndex = 0;
var oldSlideIndex = 4;

function onResponse(response){
    console.log('risposta ricevuta');
    return response.json();
}

function onImg(json){
    console.log(json);
    const header = document.querySelector('header');
    let new_img;

    for(result of json.results){
        new_img = document.createElement('img');
        new_img.src = result.urls.regular;
        new_img.classList.add('slides','fadeOut');
        header.appendChild(new_img);
    }

    slideShow();
}

function slideShow(){
    const slides = document.getElementsByClassName('slides');
    if(slideIndex == slides.length)
        slideIndex=0;

    slides[oldSlideIndex].classList.remove('fadeIn');
    slides[oldSlideIndex].classList.add('fadeOut');
    slides[slideIndex].classList.remove('fadeOut');
    slides[slideIndex].classList.add('fadeIn');
    oldSlideIndex = slideIndex;
    slideIndex++;
    setTimeout(slideShow,10000);
}

function richiesta(){
    fetch('unsplash_api.php').then(onResponse).then(onImg);
}

richiesta();