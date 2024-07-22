$('#modalEditarEvidencia').on('hidden.bs.modal', function () {
    $('#inev_nombre_edit').val('');
    $('#inev_descripcion_edit').val('');
    $('#form-editar-evidencia').attr('action', '');
});

function agregar() {
    $('#modalAgregarEvidencia').modal('show');
}

function editar(inev_codigo, inev_nombre, inev_descripcion) {
    $('#inev_nombre_edit').val(inev_nombre);
    $('#inev_descripcion_edit').val(inev_descripcion);
    $('#form-editar-evidencia').attr('action', window.location.origin+'/admin/iniciativa/evidencia/'+inev_codigo);
    $('#modalEditarEvidencia').modal('show');
}

function convertToLowerCase(input) {
    const file = input.files[0];

    if (file) {
      const reader = new FileReader();

      reader.onload = function (e) {
        const content = e.target.result;
        input.value = content.toLowerCase();

        // Cambiar la extensión del archivo a minúsculas
        const fileName = file.name;
        const parts = fileName.split('.');
        if (parts.length > 1) {
          const extension = parts.pop();
          const newName = parts.join('.') + '.' + extension.toLowerCase();
          file.name = newName;
        }
      };
      console.log(file);

      reader.readAsText(file);
    }
}
