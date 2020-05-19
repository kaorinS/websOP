function countLength(str, field, maxNumber, field2) {
  const length = str.length,
    max = maxNumber,
    attention = document.getElementById(field2);
  document.getElementById(field).innerHTML = length;
  attention.style.display = length > max ? "block" : "none";
}
