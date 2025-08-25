document.addEventListener('DOMContentLoaded', () => {
  // Client-side validation hints
  const forms = document.querySelectorAll('form[novalidate]');
  forms.forEach(form => {
    form.addEventListener('submit', (e) => {
      if (!form.checkValidity()) {
        e.preventDefault();
        [...form.elements].forEach(el => el.reportValidity && el.reportValidity());
      }
    });
  });

  // Toggle password visibility
  document.querySelectorAll('[data-toggle="pwd"]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const input = document.getElementById(btn.dataset.target);
      if(!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
      btn.textContent = input.type === 'password' ? 'Show' : 'Hide';
    });
  });
});
