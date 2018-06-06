var fileDaCaricare;

var buttonsDisabled=[];

var buttonsAll=[];

function stopLoader() {
	fileDaCaricare--;
	if (fileDaCaricare==0) {
		document.getElementById("loader").style.display='none';
		var buttons = document.getElementsByTagName('input');
		for (var i = 0; i < buttons.length; i++) {
			var button = buttons[i];
			if (buttonsDisabled.indexOf(button)==-1 && buttonsAll.indexOf(button)!=-1) {
				button.disabled=false;
			}
		}
		buttonsDisabled=[];
		buttonsAll=[];
	}
	
}

function startLoader() {
	document.getElementById("loader").style.display='block';
	
	var buttons = document.getElementsByTagName('input');
	for (var i = 0; i < buttons.length; i++) {
		var button = buttons[i];
		if (button.disabled==true) {
			buttonsDisabled.push(button);
		}
		button.disabled=true;
		buttonsAll.push(button);
	}
}

function loads3Table() {
	
	alert("gfds");
}

function s3ImageClick() {
    $('#imgupload').trigger('click');             
}


function onFileChoosen(){
	
	
	startLoader();
	var files = document.getElementById("imgupload").files;
	fileDaCaricare = files.length;
	for (var j=0;j<files.length;j++) {
		(function () {
			var file = files[j];
						
			if (file.name.indexOf("#")>-1 || file.name.indexOf("*")>-1) {
				alert("Il nome del file non deve contenere i caratteri #,*  ");
				stopLoader();
				return;
			}
			
			var namesLowerCase = [];
			for (var i = 0; i < names.length; i++) {
				namesLowerCase.push(names[i].toLowerCase());
			}
			if (file!=null && namesLowerCase.indexOf(file.name.toLowerCase())>-1) {
				alert("Non è possibile caricare due file con lo stesso nome");
				stopLoader();
				return;
			}
			
			var quotedNamesLowerCase = [];
			for (var i = 0; i < quotedNames.length; i++) {
				quotedNamesLowerCase.push(quotedNames[i].toLowerCase().toLowerCase());
			}
			if (file!=null && quotedNamesLowerCase.indexOf(file.name)>-1) {
				alert("Non è possibile caricare un file con lo stesso nome di un file quotato");
				stopLoader();
				return;
			}
			
			if (file!=null && names.indexOf(file.name)==-1 && quotedNames.indexOf(file.name)==-1) {
				
				if ( file.type.match('image.*') && document.getElementById("flgwatermark").value==1) {
					addWatermark(file);
					return;
				}
				
				if (file.size > 600000 && file.type.match('image.*')) {
					var r = confirm("Attenzione! L'immagine "+file.name+" è troppo grande. Vuoi ridimensionarla? Il ridimensionamento causerà una perdita di qualità e il rallentamento dell'upload.");
					if (r == true) {
						getFileRidimensionato1(file);
					} else {
						stopLoader();
					}
					return;
				}
				
				cacheFile(file);
			} 
		}()); 
	}
	document.getElementById("imgupload").value=null;
}

function upLoadNewFile(flgWatermark){
	document.getElementById("flgwatermark").value=flgWatermark;
	$('#imgupload').trigger('click');      
}

function cambioDidascalia(name,row,tag,id,size,didascaliaInput) {
	
	var namesFiles = document.getElementById("s3filelist").value.split("*");
	var newValue="";
	var trueName = name+"#"+id+"#"+size+"#"+tag+"#";
	
	for (var j=1;j<namesFiles.length;j++) {
		
		if (namesFiles[j].indexOf(trueName)==-1) {
			newValue=newValue+"*"+namesFiles[j];
		} else {
			newValue=newValue+"*"+trueName+didascaliaInput.value;
		}
		
	}
	document.getElementById("s3filelist").value=newValue;
	
	
}

function editDidascalia(didascaliaInput,editSaveDidascaliaButton,name,row,id,size,tag) {
	
	didascaliaInput.disabled=false;
	var oldValue=didascaliaInput.value;
	editSaveDidascaliaButton.innerHTML='<img src="./ext/MT/s3files/images/save.png" width="25px" style="marginTop:5px"/>';
	var clone = editSaveDidascaliaButton.cloneNode(true);
	editSaveDidascaliaButton.parentNode.replaceChild(clone,editSaveDidascaliaButton);
	clone.addEventListener("click",function save() {
		saveDidascalia(didascaliaInput,clone,oldValue,name,row,tag,id,size);
		
	});
	
}

function saveDidascalia(didascaliaInput,editSaveDidascaliaButton,oldValue,name,row,tag,id,size) {
	
	if (confirm('Attenzione la modifica della didascalia comporta la cancellazione dell\'immagine in linea. Sei sicuro di voler aggiornare la didascalia?')) {
		cambioDidascalia(name,row,tag,id,size,didascaliaInput);
		rimuoviInLinea(name,tag,id);
	} else {
		didascaliaInput.value=oldValue;
	}
	didascaliaInput.disabled=true;
	editSaveDidascaliaButton.innerHTML='<img src="./ext/MT/s3files/images/edit.png" width="30px" />';
	var clone = editSaveDidascaliaButton.cloneNode(true);
	editSaveDidascaliaButton.parentNode.replaceChild(clone,editSaveDidascaliaButton);
	clone.addEventListener("click",function save() {
		editDidascalia(didascaliaInput,clone,name,row,id,size,tag);
	});
	
	
}

function rimuoviInLinea(name,tag,id){
	
	var newPost=document.getElementById("message").value;
	var bbCodeToRemove="["+tag+" name="+name+" key="+id+"]";
	while (newPost.indexOf(bbCodeToRemove)>-1) {
		newPost=newPost.substring(0,newPost.indexOf(bbCodeToRemove))+newPost.substring(newPost.indexOf(bbCodeToRemove)+bbCodeToRemove.length,newPost.length);
	} 
	document.getElementById("message").value = newPost;
}



//pressione sul tasto cancella della riga della tabella
function cancella(name,row,tag,id,size,didascaliaInput){
	
	//rimozione  alla lista dei files s3list
	var namesFiles = document.getElementById("s3filelist").value.split("*");
	var newValue="";
	var didascaliaValue="";
	if (didascaliaInput!=null) {
		didascaliaValue=didascaliaInput.value;
	}
	var trueName = name+"#"+id+"#"+size+"#"+tag+"#"+didascaliaValue;
	for (var j=1;j<namesFiles.length;j++) {
		
		if (trueName.localeCompare(namesFiles[j])!=0) {
			newValue=newValue+"*"+namesFiles[j];
		}
	}
	document.getElementById("s3filelist").value=newValue;	
	
	//rimozione all'appoggio
	if (names.indexOf(name)>-1) {
		names.splice(names.indexOf(name),1);
	}
	
	//rimozione dalla tabella
	row.parentNode.removeChild(row);
	
	//rimozione dei bbcode dal post
	rimuoviInLinea(name,tag,id);
	

	//rendo invisibile la tabella se non ci sono più elementi
	if (names.length==0) {
		document.getElementById("s3-file-list-container").style.display = "none";
	}
	
	//rimozione dalla cache
	var xhr = new XMLHttpRequest();
	xhr.open('POST', './ext/MT/s3files/cached/code/delCacheFile.php', true);
	var formData = new FormData();
	formData.append('id',id);
	xhr.send(formData);
}

function cacheFile(file){
	document.getElementById("s3addfiles").disabled=true;
	var formData = new FormData();
	formData.append('file', file, file.name);
	var xhr = new XMLHttpRequest();
	xhr.open('POST', './ext/MT/s3files/cached/code/cacheFile.php', true);
	xhr.onload = function () {
		var json = JSON.parse(xhr.response);
		if (xhr.status === 200) {
			// File(s) uploaded.
			document.getElementById("s3addfiles").innerHTML = 'Aggiungi file';
			var key = "data";
			var splitResponse = json[key].split("#");
			var id=splitResponse[0];
			var tag=splitResponse[1];
			addRow(id,tag,file);
		} else if (xhr.status === 422){
			alert(json["status_message"]);
			stopLoader();
		} else {
			alert('Errore di comunicazione con il server');
			stopLoader();
		}
		document.getElementById("s3addfiles").disabled=false;

	};
	xhr.send(formData);

}



function aggiungiInLinea(name, tag, id){
	var post =document.getElementById("message");
	if (post.value.length>0) {
		var cursorPosition;
		try{
			
			//Firefox, chrome, mozilla
			cursorPosition=post.selectionStart;
			
			if (cursorPosition || cursorPosition==0) {
				var textBefore = post.value.substr(0,cursorPosition);
				var textAfter = post.value.substr(cursorPosition);
				post.value=textBefore+"\n\n["+tag+" name="+name+" key="+id+"]"+textAfter;
				//post.focus();
				if (window.getSelection) {
					window.getSelection().removeAllRanges();
				} 
				post.selectionStart = cursorPosition + post.value.length-textBefore.length-textAfter.length;
				post.selectionEnd=post.selectionStart;
				return;
			}
				
			
			
			
		} catch (err) {
		}
		post.value+="\n\n["+tag+" name="+name+" key="+id+"]";
	} else {
		post.value+="["+tag+" name="+name+" key="+id+"]";
	}
}


//aggiungo una riga  all atabella
function addRow(id,tag,file) {
				
				
        var table = document.getElementById("s3table");
		 
		var x = document.getElementById("imgupload");
				 		 
		var name=file.name;
		 
		var size=file.size/1000;
		 
		var row = table.insertRow();
		
		var cell0 = row.insertCell(0);
		
		var cell1 = row.insertCell(1);
		
		
		var cell5 =row.insertCell(2);
		var cell6 = row.insertCell(3);
		if (tag == 'image') {
			var img = document.createElement('img');
			img.src = "./ext/MT/s3files/download/linkAllegato.php?id="+id+"&tag=image";
			img.style.maxHeight="80px";
			img.style.maxWidth="100px";
			cell5.appendChild(img);
			
			var didascaliaInput=document.createElement("input");
			didascaliaInput.disabled=true;
			didascaliaInput.type = "text";
			didascaliaInput.size=65;
			didascaliaInput.maxLength=75;
			didascaliaInput.addEventListener("keyup",function checkText() {
				restrictDidascaliaInput(didascaliaInput);
			});
					
			var editSaveDidascaliaButton=document.createElement('button');
			editSaveDidascaliaButton.innerHTML='<img src="./ext/MT/s3files/images/edit.png" width="30px" />';
			editSaveDidascaliaButton.addEventListener("click",function edit() {
				editDidascalia(didascaliaInput,editSaveDidascaliaButton,name,row,id,size,tag);
			});
			editSaveDidascaliaButton.type="button";
			
			
			cell6.appendChild(didascaliaInput);
			cell6.appendChild(editSaveDidascaliaButton,editSaveDidascaliaButton);
		}
		
		
		
		var cell2 = row.insertCell(4);
		
		var cell3 = row.insertCell(5);

		var cell4 = row.insertCell(6);
		
		
		
		cell1.innerHTML = name;

		
		cell2.innerHTML = size.toString().concat(" KB") ; 
		
		var btn1 = document.createElement('input');
		btn1.style.width = "100px";
		btn1.style.height = "60px";
		btn1.type = "button";
		btn1.className = "btn";
		btn1.value = "Aggiungi in linea";
		btn1.addEventListener("click", function() {
			aggiungiInLinea(name,tag,id);
		});
		cell3.appendChild(btn1);
		
		
		
		
		var btn2 = document.createElement('input');
		btn2.style.width = "100px";
		btn2.style.height = "60px";
		btn2.type = "button";
		btn2.className = "btn";
		btn2.value = "Cancella";
		btn2.addEventListener("click",function() {
			 cancella(name,row,tag,id,size,didascaliaInput);
		});
		cell4.appendChild(btn2);
		
		names.push(name);
		
		document.getElementById("s3filelist").value=document.getElementById("s3filelist").value+"*"+name+"#"+id+"#"+size+"#"+tag+"#";//didascalia
		document.getElementById("s3-file-list-container").style.display = "block";

		
		
		stopLoader();
		
} 

function getFileRidimensionato1(file) {
	if (FileReader) {
	var reader  = new FileReader();
    reader.onload= function () {
		var i = new Image();
		i.src = reader.result;
		i.onload = function() {
			getFileRidimensionato2(file,i.width-i.width/4)
		}
		
	};
	reader.readAsDataURL(file);
	} else {
		alert("Ridimensionamento non supportato per questo browser");
	}
}

function getFileRidimensionato2(file, width) {
    
	try {   
	  
		new ImageCompressor(file, {
			quality: .9,
			width:width,
			success(resizedImage) {
				if (resizedImage.size<600000) {
					resizedImage.name=file.name;
					cacheFile(resizedImage);
				} else {
					getFileRidimensionato2(resizedImage,width-width/4);
				}	
			}, 
			error(e) {
				alert("errore durante la conversione");
			},
		});
	} catch (e) {
		alert("errore durante la conversione");
		stopLoader();
	}
		
}

function addWatermark(file) {
	var widthWatermark;
	try {
	//mi vado a prendere il watermark dell'utente
	fetch('./ext/MT/s3files/images/logo.png')
		.then(res => res.blob()) 
			.then(blob => {
				blob.name="watermark";
				blob.lastModifiedDate = new Date();
				//mi prendo la dimensione del file su cui applicare il watermark
				if (FileReader) {
					var reader  = new FileReader();
					reader.onload= function () {
						var i = new Image();
						i.src = reader.result;
						i.onload = function() {
						var logoWidthVar= logoWidth(i.width);
						var textWidthVar=textSize(i.width);
						if ((!logoWidthVar) || (!textWidthVar)) {
							alert("Errore, dimensioni foto non supportate");
							stopLoader();
							return;
						}
						widthWatermark=logoWidthVar;	
							//faccio il resize del watermark e lo mando a stampare
							new ImageCompressor(blob, {
								quality: .9,
								width: widthWatermark,
								maxHeight: i.height/4,
								success(resizedImage) {
									addWatermark2(file, resizedImage,widthWatermark,textWidthVar);
								}, 
								error(e) {
									alert("errore durante la conversione");
									stopLoader();
								},
							});
							
							
						}
					};
					reader.readAsDataURL(file);
				} else {
					alert("Ridimensionamento non supportato per questo browser");
					stopLoader();
				}	
			});
			
	} catch (e) {
		alert("errore");
		stopLoader();
	}

}	

const watermark_options = {
		init(img) {
			img.crossOrigin = 'anonymous'
		}
};

//aggiungo la scritta
function addWatermark2(file,watermarkImg,widthWatermark,textWidthVar) {
	try {
		
		watermark([file,watermarkImg ], watermark_options)
			.image(watermark.text.lowerRight(document.getElementById("s3username").value, textWidthVar+'px Josefin Slab', '#fff'))
			.then(img => {
				fetch(img.src)
					.then(res => res.blob()) // Gets the response and returns it as a blob
						.then(blob => {
							blob.name=file.name;
							blob.lastModifiedDate = new Date();
							addWatermark3(blob,watermarkImg);
						});
			});
	} catch (e) {
		alert(e);
		stopLoader();
	}
	
}

//aggiungo il logo
function addWatermark3(file,watermarkImg) {
	
	try {
		watermark([file,watermarkImg ], watermark_options)
			.image(watermark.image.lowerRight())
			.then(img => {
				fetch(img.src)
					.then(res => res.blob()) // Gets the response and returns it as a blob
						.then(blob => {
							blob.name=file.name;
							blob.lastModifiedDate = new Date();
								if (blob.size > 600000 && blob.type.match('image.*')) {
									var r = confirm("Attenzione! L'immagine "+blob.name+" è troppo grande. Vuoi ridimensionarla? Il ridimensionamento causerà una perdita di qualità e il rallentamento dell'upload.");
									if (r == true) {
										getFileRidimensionato1(blob);
									} else {
										stopLoader();
									}
									return;
								}
								cacheFile(blob);
		
						});
			});
	} catch (e) {
		alert(e);
		stopLoader();
	}

}

function restrictDidascaliaInput(input) {
	
		var regex= /[^a-z0-9\s]/gi;
		input.value = input.value.replace(regex, "");
	
}

function logoWidth(imgWidth) {
	var width=5422+(-38737-5422)/(1+Math.pow(imgWidth/1.179*Math.pow(10,11),0.0638));
	if (width<0) {
		return false;
	}
	return width;
}

function textSize(imgWidth) {
	var width=40.91+(17-41)/(1+Math.pow(imgWidth/904,5.6));
	if (width<0) {
		return false;
	}
	return width.toFixed(0);
}
