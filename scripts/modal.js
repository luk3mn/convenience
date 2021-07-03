/******  ******/
document.querySelector('#open').addEventListener("click", () => {
  document.querySelector('.box-modal').style.width='300px';
  document.querySelector('.box-modal').style.transition='.3s';
})

document.querySelector('#close').addEventListener("click", () => {
  document.querySelector('.box-modal').style.width='0px';
})

/***** BUY *****/
document.querySelector('#open-buy').addEventListener("click", () => {
  document.querySelector('.modal').classList.remove('hidden');
})

document.querySelector('#close-buy').addEventListener("click", () => {
  document.querySelector('.modal').classList.add('hidden');
})