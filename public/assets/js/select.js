const select=()=>{
    let inputs = document.querySelectorAll('input[type="date"], input[type="time"],select');
    const fields =[...inputs];
    fields.forEach(element => {
      element.addEventListener("change",function(event){
        if (event.target.value) {
          event.target.classList.add('text-contentColor2','dark:text-contentColor-dark');
          event.target.classList.remove('text-placeholder');
      } else {
        event.target.classList.remove('text-contentColor2','dark:text-contentColor-dark');
          event.target.classList.add('text-placeholder');
      }
      });
    });

}