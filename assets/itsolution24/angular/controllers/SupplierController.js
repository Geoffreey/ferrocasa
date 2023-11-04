window.angularApp.controller("SupplierController", [
  "$scope",
  "API_URL",
  "window",
  "jQuery",
  "$compile",
  "$uibModal",
  "$http",
  "$sce",
  "SupplierEditModal",
  "SupplierDeleteModal",
  "EmailModal",
  function (
    $scope,
    API_URL,
    window,
    $,
    $compile,
    $uibModal,
    $http,
    $sce,
    SupplierEditModal,
    SupplierDeleteModal,
    EmailModal
  ) {
    "use strict";

    var dt = $("#supplier-supplier-list");
    var supId;
    var i;

    var hideColums = dt.data("hide-colums").split(",");
    var hideColumsArray = [];
    if (hideColums.length) {
      for (i = 0; i < hideColums.length; i += 1) {
        hideColumsArray.push(parseInt(hideColums[i]));
      }
    }

    //================
    // Start datatable
    //================

    dt.dataTable({
      oLanguage: {
        sProcessing: "<img src='../assets/itsolution24/img/loading2.gif'>",
      },
      processing: true,
      dom: "lfBrtip",
      serverSide: true,
      ajax: API_URL + "/_inc/supplier.php",
      order: [[0, "desc"]],
      aLengthMenu: [
        [10, 25, 50, 100, 200, -1],
        [10, 25, 50, 100, 200, "All"],
      ],
      columnDefs: [
        { targets: [2, 5, 6, 7, 8, 9], orderable: false },
        { visible: false, targets: hideColumsArray },
        { className: "text-center", targets: [0, 3, 4, 5, 6, 7, 8] },
        {
          targets: [0],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(0)").html()
            );
          },
        },
        {
          targets: [1],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(1)").html()
            );
          },
        },
        {
          targets: [2],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(2)").html()
            );
          },
        },
        {
          targets: [3],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(3)").html()
            );
          },
        },
        {
          targets: [4],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(4)").html()
            );
          },
        },
        {
          targets: [5],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(5)").html()
            );
          },
        },
        {
          targets: [6],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(6)").html()
            );
          },
        },
        {
          targets: [7],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(7)").html()
            );
          },
        },
        {
          targets: [8],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(8)").html()
            );
          },
        },
        {
          targets: [9],
          createdCell: function (td, cellData, rowData, row, col) {
            $(td).attr(
              "data-title",
              $("#supplier-supplier-list thead tr th:eq(9)").html()
            );
          },
        },
      ],
      aoColumns: [
        { data: "sup_id" },
        { data: "sup_name" },
        { data: "sup_mobile" },
        { data: "total_product" },
        { data: "created_at" },
        { data: "status" },
        { data: "btn_purchase" },
        { data: "btn_view" },
        { data: "btn_edit" },
        { data: "btn_delete" },
      ],
      footerCallback: function (row, data, start, end, display) {
        var pageTotal;
        var api = this.api();
        // Elimine el formato para obtener datos enteros para la suma
        var intVal = function (i) {
          return typeof i === "string"
            ? i.replace(/[\$,]/g, "") * 1
            : typeof i === "number"
            ? i
            : 0;
        };
        // Total over all pages at column 3
        pageTotal = api
          .column(3, { page: "current" })
          .data()
          .reduce(function (a, b) {
            return intVal(a) + intVal(b);
          }, 0);
        // Actualizar pie de página
        $(api.column(3).footer()).html(pageTotal);
      },
      pageLength: window.settings.datatable_item_limit,
      buttons: [
        {
          extend: "print",
          footer: "true",
          text: '<i class="fa fa-print"></i>',
          titleAttr: "Print",
          title: "Supplier List",
          customize: function (win) {
            $(win.document.body)
              .css("font-size", "10pt")
              .append("<div><b><i>Powered by: geoffdeep.pw</i></b></div>")
              .prepend(
                '<div class="dt-print-heading"><img class="logo" src="' +
                  window.logo +
                  '"/><h2 class="title">' +
                  window.store.name +
                  "</h2><p>Printed on: " +
                  window.formatDate(new Date()) +
                  "</p></div>"
              );

            $(win.document.body)
              .find("table")
              .addClass("compact")
              .css("font-size", "inherit");
          },
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
          },
        },
        {
          extend: "copyHtml5",
          text: '<i class="fa fa-files-o"></i>',
          titleAttr: "Copy",
          title: window.store.name + " > Supplier List",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
          },
        },
        {
          extend: "excelHtml5",
          text: '<i class="fa fa-file-excel-o"></i>',
          titleAttr: "Excel",
          title: window.store.name + " > Supplier List",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
          },
        },
        {
          extend: "csvHtml5",
          text: '<i class="fa fa-file-text-o"></i>',
          titleAttr: "CSV",
          title: window.store.name + " > Supplier List",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
          },
        },
        {
          extend: "pdfHtml5",
          text: '<i class="fa fa-file-pdf-o"></i>',
          titleAttr: "PDF",
          download: "open",
          title: window.store.name + " > Supplier List",
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5],
          },
          customize: function (doc) {
            doc.content[1].table.widths = Array(
              doc.content[1].table.body[0].length + 1
            )
              .join("*")
              .split("");
            doc.pageMargins = [10, 10, 10, 10];
            doc.defaultStyle.fontSize = 8;
            doc.styles.tableHeader.fontSize = 8;
            doc.styles.tableHeader.alignment = "left";
            doc.styles.title.fontSize = 10;
            // Eliminar espacios alrededor del título de la página
            doc.content[0].text = doc.content[0].text.trim();
            // Encabezado
            doc.content.splice(1, 0, {
              margin: [0, 0, 0, 12],
              alignment: "center",
              fontSize: 8,
              text: "Printed on: " + window.formatDate(new Date()),
            });
            // Crear un pie de página
            doc["footer"] = function (page, pages) {
              return {
                columns: [
                  "Powered by geoffdeep.pw",
                  {
                    // Esta es la columna de la derecha
                    alignment: "right",
                    text: [
                      "page ",
                      { text: page.toString() },
                      " of ",
                      { text: pages.toString() },
                    ],
                  },
                ],
                margin: [10, 0],
              };
            };
            // Esta es la columna de la derecha
            var objLayout = {};
            // Grosor de la línea horizontal
            objLayout["hLineWidth"] = function (i) {
              return 0.5;
            };
            // Grosor de la línea vertical
            objLayout["vLineWidth"] = function (i) {
              return 0.5;
            };
            // Color de línea horizontal
            objLayout["hLineColor"] = function (i) {
              return "#aaa";
            };
            // Color de línea vertical
            objLayout["vLineColor"] = function (i) {
              return "#aaa";
            };
            // Relleno izquierdo de la celda.
            objLayout["paddingLeft"] = function (i) {
              return 4;
            };
            // Relleno derecho de la celda.
            objLayout["paddingRight"] = function (i) {
              return 4;
            };
            // Inyectar el objeto en el documento.
            doc.content[1].layout = objLayout;
          },
        },
      ],
    });

    //================
    // Finalizar tabla de datos
    //================

    // Create new supplier
    $(document).delegate("#create-supplier-submit", "click", function (e) {
      e.preventDefault();
      var $tag = $(this);
      var $btn = $tag.button("loading");
      var form = $($tag.data("form"));
      form.find(".alert").remove();
      var actionUrl = form.attr("action");

      $http({
        url: window.baseUrl + "/_inc/" + actionUrl,
        method: "POST",
        data: form.serialize(),
        cache: false,
        processData: false,
        contentType: false,
        dataType: "json",
      }).then(
        function (response) {
          $("#reset").trigger("click");
          $btn.button("reset");
          $(':input[type="button"]').prop("disabled", false);
          var alertMsg = response.data.msg;
          window.toastr.success(alertMsg, "Success!");

          supId = response.data.id;

          dt.DataTable().ajax.reload(function (json) {
            if ($("#row_" + supId).length) {
              $("#row_" + supId).flash("yellow", 5000);
            }
          }, false);
        },
        function (response) {
          $btn.button("reset");
          $(':input[type="button"]').prop("disabled", false);
          var alertMsg = "<div>";
          window.angular.forEach(response.data, function (value) {
            alertMsg += "<p>" + value + ".</p>";
          });
          alertMsg += "</div>";
          window.toastr.warning(alertMsg, "Warning!");
        }
      );
    });

    // Edit supplier
    $(document).delegate("#edit-supplier", "click", function (e) {
      e.stopPropagation();
      e.preventDefault();

      var d = dt.DataTable().row($(this).closest("tr")).data();

      SupplierEditModal(d);
    });

    // Delete supplier
    $(document).delegate("#delete-supplier", "click", function (e) {
      e.stopPropagation();
      e.preventDefault();
      var datatable = dt;
      var d = datatable.DataTable().row($(this).closest("tr")).data();

      SupplierDeleteModal(d);
    });

    // Oopen edit modal dialog box by query string
    if (
      window.getParameterByName("sup_id") &&
      window.getParameterByName("sup_name")
    ) {
      supId = window.getParameterByName("sup_id");
      var supName = window.getParameterByName("sup_name");
      dt.DataTable().search(supName).draw();
      dt.DataTable().ajax.reload(function (json) {
        $.each(json.data, function (index, obj) {
          if (obj.DT_RowId === "row_" + supId) {
            SupplierEditModal({ sup_id: supId, sup_name: obj.sup_name });
            return false;
          }
        });
      }, false);
    }

    /*
     // Purchase product
    $(document).delegate("#purchase-btn", "click", function (e) {
        e.stopPropagation();
        e.preventDefault();
        var supplier = dt.DataTable().row($(this).closest("tr")).data();
        PurchaseProductModal({sup_id:supplier.sup_id,sup_name:supplier.sup_name, invoice_id:null});
    });
	*/

    // Append email button into datatable buttons
    if (window.sendReportEmail) {
      $(".dt-buttons").append(
        '<button id="email-btn" class="btn btn-default buttons-email" tabindex="0" aria-controls="invoice-invoice-list" type="button" title="Email"><span><i class="fa fa-envelope"></i></span></button>'
      );
    }

    // Send supplier list through email
    $("#email-btn").on("click", function (e) {
      e.stopPropagation();
      e.preventDefault();
      dt.find(
        "thead th:nth-child(7), thead th:nth-child(8), thead th:nth-child(9), thead th:nth-child(10), tbody td:nth-child(7), tbody td:nth-child(8), tbody td:nth-child(9), tbody td:nth-child(10), tfoot th:nth-child(7), tfoot th:nth-child(8), tfoot th:nth-child(9), tfoot th:nth-child(10)"
      ).addClass("hide-in-mail");
      var thehtml = dt.html();
      EmailModal({
        template: "default",
        subject: "Supplier Listing",
        title: "Supplier Listing",
        html: thehtml,
      });
    });
  },
]);
