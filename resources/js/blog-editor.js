/**
 * Gestion de l'éditeur Quill, importation et prévisualisation
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. CONFIGURATION COMMUNE ---
    const toolbarOptions = [
        [{ 'header': [1, 2, false] }],
        ['bold', 'italic', 'underline', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['clean']
    ];

    // --- 2. INSTANCE CRÉATION ---
    const createCont = document.getElementById('editor-container');
    let createQuill; // On le déclare ici pour y avoir accès dans l'importation

    if (createCont) {
        createQuill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Rédigez votre article ici...',
            modules: { toolbar: toolbarOptions }
        });

        const form = document.querySelector('#blog-form');
        if (form) {
            form.onsubmit = function() {
                const html = createQuill.root.innerHTML;
                document.querySelector('#description_input').value = (html === '<p><br></p>') ? '' : html;
            };
        }
    }

    // --- 3. INSTANCE ÉDITION ---
    const editCont = document.getElementById('edit-editor-container');
    if (editCont) {
        window.editQuill = new Quill('#edit-editor-container', {
            theme: 'snow',
            modules: { toolbar: toolbarOptions }
        });

        const editForm = document.getElementById('edit-blog-form');
        if (editForm) {
            editForm.onsubmit = function() {
                const html = window.editQuill.root.innerHTML;
                document.getElementById('edit_description_input').value = (html === '<p><br></p>') ? '' : html;
            };
        }
    }

    // --- 4. LOGIQUE D'IMPORTATION (Word/PDF) ---
    // On lie l'importation à l'instance de CRÉATION
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    const importInput = document.getElementById('universal-import');

    if (importInput && createQuill) {
        importInput.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const fileName = file.name.toLowerCase();
            const reader = new FileReader();

            reader.onload = async function(event) {
                const arrayBuffer = event.target.result;

                if (fileName.endsWith('.docx')) {
                    mammoth.convertToHtml({arrayBuffer: arrayBuffer})
                        .then(result => insertToQuill(createQuill, result.value))
                        .catch(err => alert("Erreur Word: " + err));
                } 
                else if (fileName.endsWith('.pdf')) {
                    try {
                        const pdf = await pdfjsLib.getDocument(new Uint8Array(arrayBuffer)).promise;
                        let fullText = '';
                        for (let i = 1; i <= pdf.numPages; i++) {
                            const page = await pdf.getPage(i);
                            const textContent = await page.getTextContent();
                            fullText += textContent.items.map(item => item.str).join(' ') + '<br><br>';
                        }
                        insertToQuill(createQuill, fullText);
                    } catch (err) {
                        alert("Erreur PDF: " + err);
                    }
                }
                importInput.value = ''; 
            };
            reader.readAsArrayBuffer(file);
        });
    }

    function insertToQuill(quillInstance, html) {
        const range = quillInstance.getSelection();
        const index = range ? range.index : quillInstance.getLength();
        quillInstance.clipboard.dangerouslyPasteHTML(index, html);
    }

    // --- 5. ÉCOUTEUR POUR REMPLIR L'ÉDITEUR D'ÉDITION ---
    window.addEventListener('fill-edit-editor', function(e) {
        if (window.editQuill) {
            window.editQuill.root.innerHTML = e.detail.content;
        }
    });

    // --- 6. GESTION IMAGE COUVERTURE ---
    const mainPictureInput = document.getElementById('main_picture');
    if (mainPictureInput) {
        mainPictureInput.addEventListener('change', previewFile);
    }
});

/**
 * Fonctions globales (hors DOMContentLoaded)
 */
function previewEditFile() {
    const preview = document.getElementById('edit-image-preview');
    const noImageBlock = document.getElementById('edit-no-image');
    const file = document.getElementById('edit_main_picture').files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onloadend = function () {
            preview.src = reader.result;
            preview.classList.remove('hidden');
            noImageBlock.classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    const input = document.getElementById('main_picture');
    const container = document.getElementById('preview-container');
    const label = document.getElementById('upload-label');
    
    input.value = ""; // On vide le fichier sélectionné
    container.classList.add('hidden'); // On cache la preview
    label.classList.remove('hidden'); // ON RÉAFFICHE le bouton de sélection !
}

function openEditForm(article) {
    const container = document.getElementById('edit-article-container');
    const content = document.getElementById('edit-modal-content');
    const form = document.getElementById('edit-blog-form');

    form.action = `/admin/blog/${article.id_blog}`;
    document.getElementById('edit_title').value = article.title;
    
    const previewImg = document.getElementById('edit-image-preview');
    const noImageBlock = document.getElementById('edit-no-image');

    // Correction ici : On vérifie si path_location existe et n'est pas vide
    if (article.path_location && article.path_location !== 'null') {
        // On ajoute le slash devant seulement si c'est un chemin relatif
        previewImg.src = article.path_location.startsWith('http') ? article.path_location : '/' + article.path_location;
        previewImg.classList.remove('hidden');
        noImageBlock.classList.add('hidden');
    } else {
        previewImg.src = "";
        previewImg.classList.add('hidden');
        noImageBlock.classList.remove('hidden');
    }

    window.dispatchEvent(new CustomEvent('fill-edit-editor', { 
        detail: { content: article.description } 
    }));

    container.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; 
    setTimeout(() => {
        container.classList.remove('opacity-0');
        content.classList.remove('translate-y-10');
    }, 10);
}

function closeEditForm() {
    const container = document.getElementById('edit-article-container');
    const content = document.getElementById('edit-modal-content');
    
    container.classList.add('opacity-0');
    content.classList.add('translate-y-10');
    document.body.style.overflow = 'auto'; // Réactive le scroll
    
    setTimeout(() => container.classList.add('hidden'), 300);
}