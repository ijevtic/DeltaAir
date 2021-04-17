function ispisi(time) {
    var niz = time.split(":");
    return niz[0] + ":" + niz[1];
  }
  function formatMin(minuti) {
    minuti = parseInt(minuti);
    var s = Math.floor(minuti/60);
    var m = minuti%60;
    if(s == 0) return m + "min";
    if(m == 0) return s + "h";
    return s + "h" + m + "min";
  }
  function dodaj(time1, minuti) {
    var niz = time1.split(":");
    var h = parseInt(niz[0]);
    var m = parseInt(niz[1]);
    m += parseInt(minuti);
    h += Math.floor(m/60);
    m %= 60;
    h %= 24;
    if(h < 10) h = "0" + h;
    if(m < 10) m = "0" + m;
    return h + ":" + m;
  }
  function razlika(date1, time1, date2, time2) {
    var niz1 = date1.split("-");
    var y1 = parseInt(niz1[0]);
    var mon1 = parseInt(niz1[1]);
    var d1 = parseInt(niz1[2]);
  
    var niz2 = date2.split("-");
    var y2 = parseInt(niz2[0]);
    var mon2 = parseInt(niz2[1]);
    var d2 = parseInt(niz2[2]);
  
    var p1 = time1.split(":");
    var m1 = parseInt(p1[1]);
    var h1 = parseInt(p1[0]);
  
    var p2 = time2.split(":");
    var m2 = parseInt(p2[1]);
    var h2 = parseInt(p2[0]);
    
    var datum1 = new Date(y1, mon1, d1, h1, m1);
    var datum2 = new Date(y2, mon2, d2, h2, m2);
    var diff = datum2 - datum1;
  
    var rezM = Math.floor(diff/60000);
    var rezH = Math.floor(rezM/60);
    rezM %= 60;
    var rezD = Math.floor(rezH/24);
    rezH %= 24;
    return rezD + ":" + rezH + ":" + rezM;
  }
  function oduzmi(vreme, minuti) {
    var niz = vreme.split(":");
    var d = parseInt(niz[0]);
    var h = parseInt(niz[1]);
    var m = parseInt(niz[2]);
    minuti = parseInt(minuti);
    var h2 = Math.floor(minuti/60);
    var m2 = minuti % 60;
    m -= m2;
    h -= h2;
    if(m < 0) {
      h--;
      m += 60;
      if(h < 0) {
        h += 24;
        d--;
      }
    }
    var s = "";
    if(d > 0) s += d + "dan";
    if(h > 0) s += h + "h";
    if(m > 0) s += m + "min";
    return s;
  }
  function mesec(broj) {
    if(broj == 1) return 'Januar';
    if(broj == 2) return 'Februar';
    if(broj == 3) return 'Mart';
    if(broj == 4) return 'April';
    if(broj == 5) return 'Maj';
    if(broj == 6) return 'Jun';
    if(broj == 7) return 'Jul';
    if(broj == 8) return 'Avgust';
    if(broj == 9) return 'Septembar';
    if(broj == 10) return 'Oktobar';
    if(broj == 11) return 'Novembar';
    if(broj == 12) return 'Decembar';
  }
  function ispisiDatum(datum) {
    var niz = datum.split("-");
    return niz[2] + ". " + mesec(niz[1]) + " " + niz[0];
  }
  function porediDatume(d1, d2) {
    console.log(d1 + " " + d2);
    datum1 = new Date(d1);
    datum2 = new Date(d2);
    return datum1 <= datum2;
  }