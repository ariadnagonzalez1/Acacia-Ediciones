import './bootstrap';

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Swal = Swal;


document.addEventListener('DOMContentLoaded', function() {

    /*
    |--------------------------------------------------------------------------
    | SIDEBAR
    |--------------------------------------------------------------------------
    */

    const menuButton =
        document.getElementById('adminMenuButton');

    const sidebar =
        document.getElementById('adminSidebar');

    const closeButton =
        document.getElementById('adminSidebarClose');

    const overlay =
        document.getElementById('adminOverlay');


    function abrirSidebar() {

        if (!sidebar || !overlay) {
            return;
        }

        sidebar.classList.add('active');
        overlay.classList.add('active');

        document.body.style.overflow = 'hidden';

        if (menuButton) {
            menuButton.setAttribute(
                'aria-expanded',
                'true'
            );
        }
    }


    function cerrarSidebar() {

        if (!sidebar || !overlay) {
            return;
        }

        sidebar.classList.remove('active');
        overlay.classList.remove('active');

        document.body.style.overflow = '';

        if (menuButton) {
            menuButton.setAttribute(
                'aria-expanded',
                'false'
            );
        }
    }


    if (menuButton) {
        menuButton.addEventListener(
            'click',
            abrirSidebar
        );
    }


    if (closeButton) {
        closeButton.addEventListener(
            'click',
            cerrarSidebar
        );
    }


    if (overlay) {
        overlay.addEventListener(
            'click',
            cerrarSidebar
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ALERTAS DESPUÉS DE CREAR / EDITAR / ELIMINAR
    |--------------------------------------------------------------------------
    */

    const flashMessages =
        document.getElementById('flash-messages');


    if (flashMessages) {

        const success =
            flashMessages.getAttribute(
                'data-success'
            );

        const error =
            flashMessages.getAttribute(
                'data-error'
            );


        if (success) {

            Swal.fire({
                icon: 'success',
                title: '¡Listo!',
                text: success,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#22352b'
            });

        }


        if (error) {

            Swal.fire({
                icon: 'error',
                title: 'No se pudo realizar',
                text: error,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#22352b'
            });

        }

    }

    /*
|--------------------------------------------------------------------------
| AGREGAR LIBRO AL CARRITO SIN RECARGAR
|--------------------------------------------------------------------------
*/

    const addToCartForms =
        document.querySelectorAll('.add-to-cart-form');

    addToCartForms.forEach((form) => {

        form.addEventListener('submit', async(event) => {

            event.preventDefault();

            const button =
                form.querySelector('button[type="submit"]');

            const originalText =
                button.textContent;

            button.disabled = true;
            button.textContent = 'Agregando...';

            try {

                const response = await fetch(
                    form.action, {
                        method: 'POST',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },

                        body: new FormData(form),
                    }
                );

                if (!response.ok) {
                    throw new Error(
                        'No se pudo agregar al carrito.'
                    );
                }

                const data =
                    await response.json();


                /*
                |------------------------------------------
                | ACTUALIZAR CONTADOR
                |------------------------------------------
                */

                const cartCount =
                    document.getElementById(
                        'publicCartCount'
                    );

                if (cartCount) {

                    cartCount.textContent =
                        data.count;

                    cartCount.classList.remove(
                        'is-hidden'
                    );

                }


                /*
                |------------------------------------------
                | MENSAJE
                |------------------------------------------
                */

                if (window.Swal) {

                    Swal.fire({
                        icon: data.already_added ?
                            'info' : 'success',

                        title: data.already_added ?
                            'Ya estaba agregado' : 'Agregado al carrito',

                        text: data.message,

                        timer: 1600,

                        showConfirmButton: false,
                    });

                }

            } catch (error) {

                console.error(error);

                if (window.Swal) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Ups',
                        text: 'No se pudo agregar el libro al carrito.',
                    });

                }

            } finally {

                button.disabled = false;

                button.textContent =
                    originalText;

            }

        });

    });

    /*
|--------------------------------------------------------------------------
| AGREGAR KIT AL CARRITO SIN RECARGAR
|--------------------------------------------------------------------------
*/

    const addKitToCartForms =
        document.querySelectorAll('.add-kit-to-cart-form');

    addKitToCartForms.forEach((form) => {

        form.addEventListener('submit', async(event) => {

            event.preventDefault();

            const button =
                form.querySelector('button[type="submit"]');

            const originalText =
                button.textContent;

            button.disabled = true;
            button.textContent = 'Agregando...';

            try {

                const response = await fetch(
                    form.action, {
                        method: 'POST',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },

                        body: new FormData(form),
                    }
                );

                if (!response.ok) {
                    throw new Error(
                        'No se pudo agregar el kit.'
                    );
                }

                const data =
                    await response.json();

                /*
                |--------------------------------------------------------------------------
                | ACTUALIZAR CONTADOR
                |--------------------------------------------------------------------------
                */

                const cartCount =
                    document.getElementById(
                        'publicCartCount'
                    );

                if (cartCount) {

                    cartCount.textContent =
                        data.count;

                    cartCount.classList.remove(
                        'is-hidden'
                    );

                }

                /*
                |--------------------------------------------------------------------------
                | SWEETALERT
                |--------------------------------------------------------------------------
                */

                if (window.Swal) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Kit agregado',
                        text: data.message,
                        timer: 1600,
                        showConfirmButton: false,
                    });

                }

            } catch (error) {

                console.error(error);

                if (window.Swal) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Ups',
                        text: 'No se pudo agregar el kit al carrito.',
                    });

                }

            } finally {

                button.disabled = false;

                button.textContent =
                    originalText;

            }

        });

    });

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO PROMOCIONES
    |--------------------------------------------------------------------------
    */

    const promotionType =
        document.getElementById('tipo');

    const discountField =
        document.getElementById(
            'promotionDiscountField'
        );

    const kitField =
        document.getElementById(
            'promotionKitField'
        );


    function updatePromotionFields() {

        if (!promotionType ||
            !discountField ||
            !kitField
        ) {
            return;
        }


        const type =
            promotionType.value;


        if (type === 'descuento') {

            discountField.style.display = 'flex';

            kitField.style.display = 'none';

        } else if (type === 'kit') {

            discountField.style.display = 'none';

            kitField.style.display = 'flex';

        } else {

            discountField.style.display = 'none';

            kitField.style.display = 'none';

        }

    }


    if (promotionType) {

        updatePromotionFields();

        promotionType.addEventListener(
            'change',
            updatePromotionFields
        );

    }

    const publicMenuButton =
        document.getElementById('publicMenuButton');

    const publicNav =
        document.getElementById('publicNav');

    if (publicMenuButton && publicNav) {

        publicMenuButton.addEventListener(
            'click',
            () => {

                publicNav.classList.toggle(
                    'active'
                );

            }
        );
    }

    /*
|--------------------------------------------------------------------------
| BUSCADOR EN VIVO DEL CATÁLOGO
|--------------------------------------------------------------------------
*/

    const catalogSearchForm =
        document.getElementById('catalogSearchForm');

    const catalogSearchInput =
        document.getElementById('catalogSearchInput');

    let catalogSearchTimeout;


    if (
        catalogSearchForm &&
        catalogSearchInput
    ) {

        catalogSearchInput.addEventListener(
            'input',
            function() {

                clearTimeout(
                    catalogSearchTimeout
                );

                catalogSearchTimeout =
                    setTimeout(() => {

                        catalogSearchForm.submit();

                    }, 450);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR ELIMINACIÓN
    |--------------------------------------------------------------------------
    */

    const formulariosEliminar =
        document.querySelectorAll(
            '.form-eliminar'
        );


    formulariosEliminar.forEach(function(formulario) {

        formulario.addEventListener(
            'submit',
            function(event) {

                event.preventDefault();


                const nombre =
                    formulario.getAttribute(
                        'data-nombre'
                    ) || 'este elemento';


                Swal.fire({

                    icon: 'warning',

                    title: '¿Eliminar?',

                    text: `¿Seguro que querés eliminar "${nombre}"?`,

                    showCancelButton: true,

                    confirmButtonText: 'Sí, eliminar',

                    cancelButtonText: 'Cancelar',

                    confirmButtonColor: '#a84141',

                    cancelButtonColor: '#496454',

                    reverseButtons: true

                }).then(function(resultado) {

                    if (
                        resultado.isConfirmed
                    ) {

                        formulario.submit();

                    }

                });

            }
        );

    });

});