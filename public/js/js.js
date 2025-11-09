        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.form-control');

            inputs.forEach(input => {

                input.addEventListener('focus', function () {
                    this.parentElement.classList.add('focused');
                });


                input.addEventListener('blur', function () {
                    if (this.value === '') {
                        this.parentElement.classList.remove('focused');
                    }
                });


                if (input.value !== '') {
                    input.parentElement.classList.add('focused');
                }
            });

            const submitButton = document.querySelector('.btn-submit');
            submitButton.addEventListener('click', function (e) {

            });


            const container = document.querySelector('.login-container');
            container.style.transform = 'scale(0.95)';
            setTimeout(() => {
                container.style.transition = 'transform 0.4s ease-out';
                container.style.transform = 'scale(1)';
            }, 100);
        });