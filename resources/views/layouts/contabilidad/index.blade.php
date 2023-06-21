<!DOCTYPE html>
<html>
<head>
  <title>Mi Página Web</title>
  <style>
    body {
      display: flex;
      margin: 0;
    }

    #sidebar {
      width: 25%;
      background-color: #f2f2f2;
      padding: 20px;
    }

    #sidebar img {
    max-width: 100%;
    height: auto;
    border-radius: 50%;
  }

    #content {
      width: 75%;
      padding: 20px;
    }

    h1 {
      margin-top: 0;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
    }

    .input-cell {
      width: 100%;
    }

    .eliminar-fila {
      cursor: pointer;
    }

    .eliminar-fila:hover {
      background-color: #ffcccc;
    }

    #dataSizeContainer {
      background-color: #f2f2f2;
      padding: 10px;
      border: 1px solid black;
      border-radius: 5px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div id="sidebar">
    <h1>Información</h1>
    <img src="coffe.jpeg" alt="Imagen de perfil" style="max-width: 100%; height: auto;">
    <button onclick="window.location.href = 'http://localhost/calendario/public/Calendar'">Ir al calendario</button>
    <p>Invitado</p>
    <p>Aquí puedes colocar tu información adicional.</p>
  </div>

  <div id="content">
    <h1>Tabla de Resumen</h1>

    <table id="resumen">
      <thead>
        <tr>
          <th>Concepto</th>
          <th>Valor</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Dinero gastado</td>
          <td id="dineroGastado">0</td>
        </tr>
        <tr>
          <td>Dinero ganado</td>
          <td id="dineroGanado">0</td>
        </tr>
        <tr>
          <td>Activo actual</td>
          <td id="activoActual">0</td>
        </tr>
      </tbody>
    </table>

    <h1>Tabla Interactiva</h1>

    <div id="dataSizeContainer">
      <strong>[Cantidad de datos almacenados:]</strong>
      <span id="dataSize">Calculando...</span>
    </div>

    <table id="tabla">
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Información</th>
          <th>Cantidad</th>
        </tr>
      </thead>
      <tbody id="tablaBody">
        <!-- Filas de la tabla se agregarán aquí -->
      </tbody>
    </table>

    <button onclick="agregarFila()">Agregar Fila</button>
    <button onclick="eliminarDatos()">Eliminar todos los datos</button>
  </div>

  <script>
    function agregarFila() {
      var tipo = prompt("Ingrese el tipo (Gasto o Ingreso):");
      tipo = tipo.toLowerCase();

      // Validar que el tipo ingresado sea "gasto" o "ingreso"
      if (tipo !== "gasto" && tipo !== "ingreso") {
        alert("Tipo inválido. Solo se permiten los valores 'Gasto' o 'Ingreso'.");
        return;
      }

      var informacion = prompt("Ingrese la información:");
      var cantidad = prompt("Ingrese la cantidad:");

      // Validar que la cantidad ingresada sea un número
      if (isNaN(parseFloat(cantidad))) {
        alert("Cantidad inválida. Solo se permiten números.");
        return;
      }

      // Dar formato de moneda COP a la cantidad
      cantidad = parseFloat(cantidad).toLocaleString("es-CO", {
        style: "currency",
        currency: "COP"
      });

      var tablaBody = document.getElementById("tablaBody");

      var fila = document.createElement("tr");

      var celdaTipo = document.createElement("td");
      celdaTipo.textContent = tipo;

      var celdaInformacion = document.createElement("td");
      celdaInformacion.textContent = informacion;

      var celdaCantidad = document.createElement("td");
      celdaCantidad.textContent = cantidad;
      celdaCantidad.classList.add("eliminar-fila");

      fila.appendChild(celdaTipo);
      fila.appendChild(celdaInformacion);
      fila.appendChild(celdaCantidad);

      tablaBody.appendChild(fila);

      // Actualizar el resumen
      actualizarResumen();

      // Guardar los datos en localStorage
      guardarDatosLocalStorage();

      // Agregar eventos de eliminación de fila
      agregarEventosEliminarFila();
    }

    function agregarEventosEliminarFila() {
      var filas = document.querySelectorAll("#tablaBody tr");
      filas.forEach(function (fila) {
        var celdaCantidad = fila.querySelector(".eliminar-fila");
        celdaCantidad.addEventListener("click", function () {
          var fila = this.parentNode;
          fila.parentNode.removeChild(fila);
          actualizarResumen();
          guardarDatosLocalStorage();
          agregarEventosEliminarFila(); // Volver a agregar eventos después de eliminar la fila
        });
      });
    }

    function eliminarDatos() {
      var confirmacion = prompt("¿Estás seguro de que quieres eliminar todos los datos? (Escribe 'sí' para confirmar)");

      if (confirmacion.toLowerCase() === "sí") {
        var tablaBody = document.getElementById("tablaBody");
        tablaBody.innerHTML = "";

        // Actualizar el resumen
        actualizarResumen();

        // Guardar los datos vacíos en localStorage
        guardarDatosLocalStorage();

        // Agregar eventos de eliminación de fila
        agregarEventosEliminarFila();
      }
    }

    function actualizarResumen() {
      var tablaBody = document.getElementById("tablaBody");
      var filas = tablaBody.getElementsByTagName("tr");

      var dineroGastado = 0;
      var dineroGanado = 0;

      var formatter = new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP"
      });

      for (var i = 0; i < filas.length; i++) {
        var fila = filas[i];
        var tipo = fila.cells[0].textContent;
        var cantidad = fila.cells[2].textContent;

        cantidad = cantidad.replace(/[^0-9.,-]+/g, "").replace(/\./g, "");
        cantidad = parseFloat(cantidad);

        if (isNaN(cantidad)) {
          cantidad = 0;
        }

        if (tipo.toLowerCase() === "gasto") {
          dineroGastado += cantidad;
        } else if (tipo.toLowerCase() === "ingreso") {
          dineroGanado += cantidad;
        }
      }

      var activoActual = dineroGanado - dineroGastado;

      document.getElementById("dineroGastado").textContent = formatter.format(dineroGastado);
      document.getElementById("dineroGanado").textContent = formatter.format(dineroGanado);
      document.getElementById("activoActual").textContent = formatter.format(activoActual);

      // Actualizar el tamaño de los datos
      updateDataSize();
    }

    function guardarDatosLocalStorage() {
      var tablaData = document.getElementById("tablaBody").innerHTML;
      localStorage.setItem("tablaData", tablaData);
    }

    function updateDataSize() {
      var tablaData = document.getElementById("tablaBody").innerHTML;
      var dataSize = tablaData.length;
      document.getElementById("dataSize").textContent = dataSize + " bytes";
    }

    // Cargar los datos almacenados en localStorage al cargar la página
    window.onload = function () {
      var tablaData = localStorage.getItem("tablaData");
      if (tablaData) {
        document.getElementById("tablaBody").innerHTML = tablaData;
        agregarEventosEliminarFila();
      }
      actualizarResumen();
    };
  </script>
</body>
</html>
