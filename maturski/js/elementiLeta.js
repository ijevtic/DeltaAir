function slicica(a, klasa, vrednost) {
  var span = document.createElement("span");
  span.innerHTML = ('<i style="margin-left:10px; margin-right:6px;" class="fas ' + klasa + ' fa-2x"></i>' + vrednost);
  span.setAttribute("style", "pointer-events:none;");
  a.appendChild(span);
}

function prviGrad(red, vreme_poletanja, ime_grada) {
  var d = document.createElement("div");
  d.setAttribute("style", "margin-left:2%; padding-right:0.5%; border-right: 1.5px solid #bbb; text-align: center; vertical-align: middle;");
  var p1 = document.createElement("p");
  p1.setAttribute("class", "lead");
  p1.setAttribute("style", "margin:auto; white-space: nowrap;");
  p1.innerHTML = "Vreme poletanja: " + vreme_poletanja;
  d.append(p1);

  var p2 = document.createElement("p");
  p2.setAttribute("class", "lead");
  p2.setAttribute("style", "margin:auto; font-weight:500;");
  p2.innerHTML = ime_grada;
  d.append(p2);
  red.appendChild(d);
}

function drugiGrad(red, vreme_sletanja, ime_grada) {
  var d = document.createElement("div");
  d.setAttribute("style", "padding-left:0.5%; padding-right:0.5%; border-right: 1.5px solid #bbb; text-align: center; vertical-align: middle;");
  var p1 = document.createElement("p");
  p1.setAttribute("class", "lead");
  p1.setAttribute("style", "margin:auto; white-space: nowrap;");
  p1.innerHTML = "Vreme sletanja: " + vreme_sletanja;
  d.append(p1);

  var p2 = document.createElement("p");
  p2.setAttribute("class", "lead");
  p2.setAttribute("style", "margin:auto; font-weight:500;");
  p2.innerHTML = ime_grada;
  d.append(p2);
  red.appendChild(d);
}

function ispisLet(red, vreme_leta) {
  var d = document.createElement("div");
  d.setAttribute("style", "padding-left:0.5%; padding-right:0.3%; border-right: 1.5px solid #bbb; text-align: center; vertical-align: middle;");
  var p1 = document.createElement("p");
  p1.setAttribute("class", "lead");
  p1.setAttribute("style", "margin:auto;");
  p1.innerHTML = vreme_leta;
  d.append(p1);

  var i = document.createElement("i");
  i.setAttribute("class", "fas fa-plane fa-2x");
  d.append(i);
  red.appendChild(d);
}

function vremeCekanja(red, vreme) {
  var d = document.createElement("div");
  d.setAttribute("style", "padding-left:0.5%; padding-right:0.3%; border-right: 1.5px solid #bbb; text-align: center; vertical-align: middle;");
  var p1 = document.createElement("p");
  p1.setAttribute("class", "lead");
  p1.setAttribute("style", "margin:auto;");
  p1.innerHTML = vreme;
  d.append(p1);

  var i = document.createElement("i");
  i.setAttribute("class", "fas fa-clock fa-2x");
  d.append(i);
  red.appendChild(d);
}

function treciGrad(red, vreme_sletanja, ime_grada) {
  var d = document.createElement("div");
  d.setAttribute("style", "padding-left:0.5%;  margin-right:1%; text-align: center; vertical-align: middle;");
  var p1 = document.createElement("p");
  p1.setAttribute("class", "lead");
  p1.setAttribute("style", "margin:auto; white-space: nowrap;");
  p1.innerHTML = "Vreme sletanja: " + vreme_sletanja;
  d.append(p1);

  var p2 = document.createElement("p");
  p2.setAttribute("class", "lead");
  p2.setAttribute("style", "margin:auto; font-weight:500;");
  p2.innerHTML = ime_grada;
  d.append(p2);
  red.appendChild(d);
}