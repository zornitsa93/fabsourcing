require('./bootstrap');

import $ from 'jquery';
window.$ = window.jQuery = $;

$(document).ready(function() {
    $('#tabs li').on('click', function() {
        var tab = $(this).data('tab');

        $('#tabs li').removeClass('is-active');
        $(this).addClass('is-active');

        $('#tab-content>div').removeClass('is-active');
        $('div[data-content="' + tab + '"]').addClass('is-active');
    });
    $('#tabs-content li').on('click', function() {
        var tab = $(this).data('tab');

        $('#tabs-content li').removeClass('is-active');
        $(this).addClass('is-active');

        $('#tab-content-c>div').removeClass('is-active');
        $('#tab-content-c div[data-content="' + tab + '"]').addClass('is-active');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.editor-area').forEach((el) => {
        if (el.classList.contains('quill-initialized')) return;

        const container = document.createElement('div');
        container.style.minHeight = '200px';
        el.parentNode.insertBefore(container, el);
        el.style.display = 'none';

        const quill = new Quill(container, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    ['link', 'image'],
                    ['clean'],
                ],
            },
        });

        if (el.value) {
            quill.clipboard.dangerouslyPasteHTML(el.value);
        }

        quill.on('text-change', function () {
            el.value = quill.root.innerHTML;
        });

        el.classList.add('quill-initialized');
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const el = document.getElementById('success-message');
    if (el) {
        setTimeout(() => {
            el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
            el.style.opacity = "0";
            el.style.transform = "translateY(-10px)";
            setTimeout(() => el.remove(), 600);
        }, 3000); // след 3 секунди
    }
});