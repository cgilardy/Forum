function insertTag(startTag, endTag, textareaId, tagType) {
        var field  = document.getElementById(textareaId); 
        var scroll = field.scrollTop;
        field.focus();
         
        /* === Partie 1 : on r�cup�re la s�lection === */
        if (window.ActiveXObject) {
                var textRange = document.selection.createRange();            
                var currentSelection = textRange.text;
        } else {
                var startSelection   = field.value.substring(0, field.selectionStart);
                var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
                var endSelection     = field.value.substring(field.selectionEnd);               
        }
         
        /* === Partie 2 : on analyse le tagType === */
        if (tagType) {
                switch (tagType) {
                        case "lien":
                                endTag = "</lien>";
								if (currentSelection) { // Il y a une s�lection
										if (currentSelection.indexOf("http://") == 0 || currentSelection.indexOf("https://") == 0 || currentSelection.indexOf("ftp://") == 0 || currentSelection.indexOf("www.") == 0) {
												// La s�lection semble �tre un lien. On demande alors le libell�
												var label = prompt("Quel est le libell� du lien ?") || "";
												startTag = "<lien url=\"" + currentSelection + "\">";
												currentSelection = label;
										} else {
												// La s�lection n'est pas un lien, donc c'est le libelle. On demande alors l'URL
												var URL = prompt("Quelle est l'url ?");
												startTag = "<lien url=\"" + URL + "\">";
										}
								} else { // Pas de s�lection, donc on demande l'URL et le libelle
										var URL = prompt("Quelle est l'url ?") || "";
										var label = prompt("Quel est le libell� du lien ?") || "";
										startTag = "<lien url=\"" + URL + "\">";
										currentSelection = label;                     
								}
                        break;
                        case "citation":
                                endTag = "</citation>";
								if (currentSelection) { // Il y a une s�lection
										if (currentSelection.length > 30) { // La longueur de la s�lection est plus grande que 30. C'est certainement la citation, le pseudo fait rarement 20 caract�res
												var auteur = prompt("Quel est l'auteur de la citation ?") || "";
												startTag = "<citation nom=\"" + auteur + "\">";
										} else { // On a l'Auteur, on demande la citation
												var citation = prompt("Quelle est la citation ?") || "";
												startTag = "<citation nom=\"" + currentSelection + "\">";
												currentSelection = citation;    
										}
								} else { // Pas de selection, donc on demande l'Auteur et la Citation
										var auteur = prompt("Quel est l'auteur de la citation ?") || "";
										var citation = prompt("Quelle est la citation ?") || "";
										startTag = "<citation nom=\"" + auteur + "\">";
										currentSelection = citation;    
								}
                        break;
                }
        }
         
        /* === Partie 3 : on ins�re le tout === */
        if (window.ActiveXObject) {
                textRange.text = startTag + currentSelection + endTag;
                textRange.moveStart("character", -endTag.length - currentSelection.length);
                textRange.moveEnd("character", -endTag.length);
                textRange.select();     
        } else {
                field.value = startSelection + startTag + currentSelection + endTag + endSelection;
                field.focus();
                field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
        } 
 
        field.scrollTop = scroll;     
}