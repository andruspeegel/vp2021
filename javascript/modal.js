let photoId;
let photoDir = "upload_photos_normal/";

window.onload = function(){
	//loen kokku pisipildid ja määran kõigile hiirekliki kuulamise
	let allThumbs = document.querySelector("#gallery").querySelectorAll(".thumbs");
	//console.log(allThumbs);
	for(let i = 0; i < allThumbs.length; i++){
		allThumbs[i].addEventListener("click", openModal);
	}
	document.querySelector("#modalclose").addEventListener("click", closeModal);
}

function openModal(e){
	//e.target
	document.querySelector("#modalimage").src = photoDir + e.target.dataset.fn;
	document.querySelector("#modalcaption").innerHTML = e.target.alt;
	photoId = e.target.dataset.id;
	for(let i = 1; i < 6; i++){
		document.querySelector("#rate" + i).checked = false;
	}
	document.querySelector("#modalarea").style.display = "block";
	document.querySelector("#storeRating").addEventListener("click", storeRating);
}

function closeModal(){
	document.querySelector("#modalarea").style.display = "none";
	document.querySelector("#modalimage").src = "/pics/empty.png";
	document.querySelector("#modalcaption").innerHTML = e.target.alt;
	document.querySelector("#avgRating").innerHTML = "";
}

function storeRating(){
	let rating = 0;
	for(let i = 1; i < 6; i ++){
		if(document.querySelector("#rate" + i).checked){
			rating = i;
		}
	}
	if(rating > 0){
		//AJAX
		let webRequest = new XMLHttpRequest();
		webRequest.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				document.querySelector("#avgRating").innerHTML = "Keskmine hinne: " + this.responseText;
				document.querySelector("#storeRating").removeEventListener("click", storeRating);
			}
		};
		webRequest.open("GET", "store_photorating.php?photo=" + photoId + "&rating=" + rating, true);
		webRequest.send();
	}
}