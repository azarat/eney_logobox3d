<div class="import">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="productRelations">Products and relations file</label>
                <input type="file" id="productRelations" name="productsRelations">
                <p class="help-block">Upload file with products and their relations</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="import__run btn btn-sm btn-success">Run import</div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3">
            <div class="import__status" role="alert"></div>
        </div>
    </div>
</div>
<script>
    $(function () {
        let importBtnEl = document.querySelector('.import__run');

        importBtnEl.addEventListener('click', function () {
            const formData = new FormData();

            const file = document.querySelector('input#productRelations').files[0];
            formData.append('relationsFile', file);

            const Http = new XMLHttpRequest();
            const url='/api/import/product-relations/manually';
            Http.open("POST", url);
            Http.send(formData);

            setImportStatus('In progress...', ['alert', 'alert-info']);

            Http.onreadystatechange = (e) => {
                let jsonResopnse = JSON.parse(Http.responseText);
                let resultText = '';
                let classes = [];
                console.log(jsonResopnse);
                if (jsonResopnse.status == 'success') {
                    resultText = 'Successfully finished';
                    classes.push('alert', 'alert-success');
                } else {
                    resultText = 'Failed';
                    classes.push('alert', 'alert-danger');
                }
                setImportStatus(resultText, classes);
            }
        });

        function setImportStatus(text, classes) {
            let statusEl = document.querySelector('.import__status');
            statusEl.textContent = text;

            statusEl.className = 'import__status';
            for (let i = 0; i < classes.length; i++) {
                statusEl.classList.add(classes[i]);
            }
        }
    });
</script>
