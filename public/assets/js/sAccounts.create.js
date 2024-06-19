$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();
    const toastsuccess = new bootstrap.Toast(document.getElementById("toastsuccess"));
    const toasterror = new bootstrap.Toast(document.getElementById("toasterror"));
    const toastwarning = new bootstrap.Toast(document.getElementById("toastwarning"));
  
    var table = $('#particulars-table').DataTable({
      processing: true,
      serverSide: false,  // Disable server-side mode since we're handling it manually
      'dom': '<"wrapper"Bfritp>',
      'order': [[0, "desc"]],
      'language': {
          'emptyTable': "No data, try using the filter"
      },
      'paging': false,
      'ordering': true,
      'info': false,
      'searching': false,
      'pageLength': 10,
      destroy: true,  // Destroy the table if it exists
  });
  
    var table = $('#classifications-table').DataTable({
      processing: true,
      serverSide: false,  // Disable server-side mode since we're handling it manually
      'dom': '<"wrapper"Bfritp>',
      'order': [[0, "desc"]],
      'language': {
          'emptyTable': "No data, try using the filter"
      },
      'paging': false,
      'ordering': true,
      'info': false,
      'searching': false,
      'pageLength': 10,
      destroy: true,  // Destroy the table if it exists
  });
  
  
      // Variable to store the clicked button
      var clickedButton = null;
  
      $(document).on('click', '.search-particular', function() {
          clickedButton = $(this);
          var offcanvas = new bootstrap.Offcanvas(document.getElementById('searchParticularOffcanvas'));
          offcanvas.show();
      });
  
      // Trigger for Search Classification Offcanvas
      $(document).on('click', '.search-classification', function() {
        clickedButton = $(this);  // Store the clicked button to use later for populating the correct fields
        var offcanvas = new bootstrap.Offcanvas(document.getElementById('searchClassificationOffcanvas'));
        offcanvas.show();
    });
      $(document).on('submit', '#ftr-particulars', function(e) {
          e.preventDefault();
          var arr = $(this).serialize();
          showOverlay('Searching particulars...');
  
          axios.post(base_url + "/ibcas/particular/ftr-particulars", arr)
              .then(function(response) {
                  var data = response.data.TableContent;
  
                  // Destroy the existing DataTable instance if it exists
                  if ($.fn.DataTable.isDataTable('#particulars-table')) {
                      $('#particulars-table').DataTable().destroy();
                  }
  
                  // Initialize DataTable with the fetched data
                  table = $('#particulars-table').DataTable({
                      data: data,
                      fnCreatedRow: function(nRow, data, iDisplayIndex) {
                          $(nRow).attr('data-id', data.id);
                      },
                      columns: [
                          { data: 'particular_name' },
                          { data: 'parTypeName' },
                      ],
                      'dom': '<"wrapper"Bfritp>',
                      'order': [[0, "desc"]],
                      "language": {
                          "emptyTable": "No Record Found"
                      },
                      'paging': true,
                      'ordering': true,
                      'info': false,
                      'searching': false,
                      "pageLength": 10,
                  });
  
                  $('[data-toggle="tooltip"]').tooltip();
              })
              .catch(function(error) {
                  console.log(error);
              });
  
          hideOverlay();
      });
  
      $('#particulars-table tbody').on('click', 'tr', function() {
          var data = table.row(this).data();
          if (!data) {
              console.error('No data found for this row.');
              return;
          }
  
          var rowId = data.id;
          var particularName = data.particular_name;
  
          // Populate the corresponding input field with the selected row data
          if (clickedButton) {
              var inputGroup = clickedButton.closest('.input-group');
              inputGroup.find('input[name="searchParticular[]"]').val(particularName);
              inputGroup.find('input[name="particularID[]"]').val(rowId);
          }
  
          var offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('searchParticularOffcanvas'));
          offcanvas.hide();
      });
  
  
  
  
  
      $(document).on('submit', '#ftr-classifications', function(e) {
          e.preventDefault();
        //   alert('test');
          var arr = $(this).serialize();
          showOverlay('Searching classifications...');
  
          axios.post(base_url + "/ibcas/particular/ftr-classifications", arr)
              .then(function(response) {
                  var data = response.data.TableContent;
  
                  // Destroy the existing DataTable instance if it exists
                  if ($.fn.DataTable.isDataTable('#classifications-table')) {
                      $('#classifications-table').DataTable().destroy();
                  }
  
                  // Initialize DataTable with the fetched data
                  table = $('#classifications-table').DataTable({
                      data: data,
                      fnCreatedRow: function(nRow, data, iDisplayIndex) {
                          $(nRow).attr('data-id', data.id);
                      },
                      columns: [
                          { data: 'class_code' },
                          { data: 'class_name' },
                      ],
                      'dom': '<"wrapper"Bfritp>',
                      'order': [[0, "desc"]],
                      "language": {
                          "emptyTable": "No Record Found"
                      },
                      'paging': true,
                      'ordering': true,
                      'info': false,
                      'searching': false,
                      "pageLength": 10,
                  });
  
                  $('[data-toggle="tooltip"]').tooltip();
              })
              .catch(function(error) {
                  console.log(error);
              });
  
          hideOverlay();
      });
  
      $('#classifications-table tbody').on('click', 'tr', function() {
        var data = table.row(this).data(); // Ensure 'table' variable is the DataTable instance for classifications
        if (!data) {
            console.error('No data found for this row.');
            return;
        }
    
        var rowId = data.id; // Ensure 'id' and 'class_name' are correct data property names
        var classificationName = data.class_name;
    
        if (clickedButton) {
            var inputGroup = clickedButton.closest('.input-group');
            inputGroup.find('input[name="searchClassification[]"]').val(classificationName);
            inputGroup.find('input[name="ClassificationID[]"]').val(rowId);
        }
    
        var offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('searchClassificationOffcanvas'));
        offcanvas.hide();
    });
    
  
    // getParticularType();
    hideOverlay();
    (function() {
      'use strict';
      window.addEventListener('load', function() {
          // Function to check if the first 11 characters of subcode match the parentCodelabel value
          function checkSubcode() {
              const parentCodelabel = $('#parentCodelabel').val();
              const subcode = $('#subcode').val();
  
              // Get the first 11 characters of subcode
              const subcodePrefix = subcode.substring(0, 11);
  
              // Check if they match
              if (subcodePrefix === parentCodelabel) {
                  $('#subcode').css('border-color', '');
                  return true;
              } else {
                  $('#subcode').css('border-color', 'red');
                  Swal.fire({
                      icon: 'error',
                      title: 'Subcode Mismatched',
                      text: 'Please make sure that the Subcode match the Parent code',
                  });
                  return false;
              }
          }
  
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');
  
          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
              form.addEventListener('submit', function(event) {
                  var checkboxes = form.querySelectorAll('input[name="options[]"]');
                  var isValidCheckbox = Array.prototype.some.call(checkboxes, function(checkbox) {
                      return checkbox.checked;
                  });
  
                  if (!isValidCheckbox) {
                      // Add validation feedback to all checkboxes
                      Array.prototype.forEach.call(checkboxes, function(checkbox) {
                          checkbox.classList.add('is-invalid');
                          checkbox.setAttribute('required', true); // Ensure 'required' attribute is set
                      });
                      event.preventDefault();
                      event.stopPropagation();
                      $('#tstwarning strong').text('Please select at least one option.');
                      $('#tstwarning').show();
                  } else {
                      // Remove validation feedback from all checkboxes
                      Array.prototype.forEach.call(checkboxes, function(checkbox) {
                          checkbox.classList.remove('is-invalid');
                          checkbox.removeAttribute('required'); // Remove 'required' attribute
                      });
                  }
  
                  if (form.checkValidity() === false || !checkSubcode()) {
                      event.preventDefault();
                      event.stopPropagation();
                      $('#tstwarning strong').text('Please fill all required fields...');
                      $('#tstwarning').show();
                  } else {
                      $('#tstwarning').hide();
                  }
  
                  form.classList.add('was-validated');
              }, false);
          });
  
          // Add change event listener to checkboxes to dynamically handle 'required' attribute
          var checkboxElements = document.querySelectorAll('input[name="options[]"]');
          checkboxElements.forEach(function(checkbox) {
              checkbox.addEventListener('change', function() {
                  var isAnyChecked = Array.prototype.some.call(checkboxElements, function(el) {
                      return el.checked;
                  });
  
                  checkboxElements.forEach(function(el) {
                      if (isAnyChecked) {
                          el.removeAttribute('required');
                      } else {
                          el.setAttribute('required', true);
                      }
                  });
              });
          });
      }, false);
  })();
  
  (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation2');
        
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
    $(":input").inputmask();
    $("#effectivity_year").inputmask({
        "mask": "9999"
    });
    $("#subcode").inputmask({
        "mask": "9-99-99-999-99"
    });
  
    $(document).on('click', '#btSearchAccount', function() {
        $('#offCanvasParentCode').offcanvas('show');
    })
  
    $(document).on('submit', '#ftr-cAccounts', function(e) {
        e.preventDefault();
        var arr = $(this).serialize();
        showOverlay('Searching...');
        // Show processing indicator  
        var table = $('#chartAccounts-table').DataTable({
            processing: true,
            serverSide: false, // Disable server-side mode since we're handling it manually
            'dom': '<"wrapper"Bfritp>',
            'order': [
                [0, "desc"]
            ],
            'language': {
                'emptyTable': "Loading..."
            },
            'paging': false,
            'ordering': true,
            'info': false,
            'searching': false,
            'pageLength': 10,
            destroy: true, // Destroy the table if it exists
        });
  
        axios.post(base_url + "/ibcas/cAccounts/ftr-accounts", arr)
            .then(function(response) {
  
                var data = response.data.TableContent;
                hideOverlay();
                // Destroy the existing DataTable instance
                table.destroy();
  
                // Initialize DataTable with the fetched data
                table = $('#chartAccounts-table').DataTable({
                    data: data,
                    fnCreatedRow: function(nRow, data, iDisplayIndex) {
                        $(nRow).attr('data-id', data.id);
                        // $(nRow).attr('data-particular_type_id', data.particular_type_id);
                    },
                    columns: [{
                            data: 'effectivity_year'
                        },
                        {
                            data: 'code'
                        },
                        {
                            data: 'title'
                        },
                        {
                            data: 'account_type'
                        },
  
                    ],
                    'dom': '<"wrapper"Bfritp>',
                    'order': [
                        [0, "desc"]
                    ],
                    "language": {
                        "emptyTable": "No Record Found"
                    },
                    'paging': true,
                    'ordering': true,
                    'info': false,
                    'searching': false,
                    "pageLength": 10,
                });
  
  
                $('[data-toggle="tooltip"]').tooltip();
            })
            .catch(function(error) {
                console.log(error);
            });
  
    });
  
    $('#chartAccounts-table tbody').on('click', 'tr', function() {
        var dataId = $(this).data('id');
  
        axios.get(base_url + "/ibcas/cAccounts/getAccountInfo/" + dataId)
            .then(function(response) {
                var data = response.data.AccountInfo[0];
                // console.log(data);
                $('#account_id').val(data.id);
                $('#parentCodelabel').val(data.code);
                $('#subcode').val(data.code);
                $('#subcode').attr('disabled', false);
            })
            .catch(function(error) {
                console.log(error);
            });
  
  
  
    });
  
    $(document).on('submit', '#frm-addSubAccount', function(e) {
      e.preventDefault();
      
      var formData = $(this).serialize();
      var particularClassification = [];
  
      // Loop through each dynamic section to create the object structure
      $('.inputcount').each(function(index) {
          var particular = $(this).find('input[name="particularID[]"]').val();
          var classification = $(this).find('input[name="ClassificationID[]"]').val();
          var nChecked = $(this).find('input[name="dynamicCheckboxN[]"]').is(':checked') ? 'checked' : 'unchecked';
          var rChecked = $(this).find('input[name="dynamicCheckboxR[]"]').is(':checked') ? 'checked' : 'unchecked';
          var cChecked = $(this).find('input[name="dynamicCheckboxC[]"]').is(':checked') ? 'checked' : 'unchecked';
  
          particularClassification.push({
              part: particular,
              class: classification,
              N: nChecked,
              R: rChecked,
              C: cChecked
          });
  
      });
        //     // Collect the checked checkbox values
        var checkboxes = $(this).find('input[name="options[]"]:checked');
        var values = [];
        checkboxes.each(function() {
            values.push($(this).val());
        });
  
        // Join the values with a comma
        var joinedValues = values.join(',');
      //   console.log(particularClassification);
      //   return false;
      // var dataToSend = {
      //     formData: formData,
      //     particularClassification: particularClassification
      // };
      var arr = $(this).serialize() + '&type=' + encodeURIComponent(joinedValues) + '&particularClassification=' + encodeURIComponent(JSON.stringify(particularClassification));
  
      // console.log(arr);
      // return false;
      Swal.fire({
          title: 'Save account?',
          text: "",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Save'
      }).then((result) => {
          if (result.isConfirmed) {
              showOverlay('Saving Account......')
              axios.post(base_url + "/ibcas/sAccounts/post-accounts", arr)
                  .then(function(response) {
                      hideOverlay();
                      if (response.data.status == 200) {
                          $('#tstsuccess strong').text(response.data.message);
                          toastsuccess.show();
                          setTimeout(function() {
                              toastsuccess.hide();
                              var url = base_url + "/ibcas/sAccounts/";
                              window.location.href = url;
                          }, 2000);
                      } else {
                          $('#tsterror strong').text(response.data.message);
                          toasterror.show();
                      }
                  })
                  .catch(function(error) {
                      console.log(error);
                  });
          } else {
              // $('#frm_submit').attr('hidden',false);
              // $('#frm_loading').attr('hidden',true);
          }
      });
  });
  
    $('#addSectionBtn').on('click', function() {
      var newSection = `
          <div class="inputcount">
          <div class="row mb-3 dynamic-section">
          <input type="hidden" name="secCounter[]">
              <div class="col">
                  <label for="searchParticular" class="form-label">Search Particular:</label>
                  <div class="input-group">
                      <input type="hidden" name="particularID[]" class="form-control">
                      <input type="text" class="form-control" name="searchParticular[]" readonly >
                      <button class="btn btn-primary search-particular" type="button">Search Particular</button>
                  </div>
              </div>
              <div class="col">
                    <label for="searchClassification" class="form-label">Search Classification:</label>
                    <div class="input-group">
                        <input type="hidden" name="ClassificationID[]" class="form-control">
                        <input type="text" class="form-control" name="searchClassification[]" readonly >
                        <button class="btn btn-primary search-classification" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchClassificationOffcanvas">Search Classification</button>
                    </div>
                </div>

          </div>
          <div class="row mb-3 dynamic-section">
              <div class="col">
                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="dynamicCheckboxN[]" value="N">
                      <label class="form-check-label checkbox-label">N</label>
                  </div>
                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="dynamicCheckboxR[]" value="R">
                      <label class="form-check-label checkbox-label">R</label>
                  </div>
                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" name="dynamicCheckboxC[]" value="C">
                      <label class="form-check-label checkbox-label">C</label>
                  </div>
                  <a href="#" class="btn btn-link remove-section">Remove</a>
              </div>
          </div>
          </div>`;
      $('#dynamic-content').append(newSection);
  });
  
  $(document).on('click', '.remove-section', function(e) {
      e.preventDefault();
      $(this).closest('.dynamic-section').prev('.dynamic-section').remove();
      $(this).closest('.dynamic-section').remove();
  });
  
  $(document).on('change','.optcbx',function(){
    // $(this).attr('data-val');
    if ($('#noParticular').is(':checked')) {
      // If #forCashTicket is checked
      $('#particularDiv').attr('hidden',true);
      // Add your logic here
  } else {
      // If #forCashTicket is not checked
      $('#particularDiv').attr('hidden',false);
      // console.log("#noParticular is not checked");
      // Add your logic here
  }
  
  
  
  
  });
  
  
  function checkSubcode() {
      const parentCodelabel = $('#parentCodelabel').val();
      const subcode = $('#subcode').val();
  
      // Get the first 11 characters of subcode
      const subcodePrefix = subcode.substring(0, 11);
  
      // Check if they match
      if (subcodePrefix === parentCodelabel) {
          // console.log("The first 11 characters of subcode match the parentCodelabel.");
          // Add your logic here for when they match
          return true;
  
  
      } else {
          // console.log("The first 11 characters of subcode do not match the parentCodelabel.");
          // Add your logic here for when they do not match
          return false;
      }
  }
  
  
  // function getListParticulars(){
  //     axios.get(base_url + "/ibcas/sAccounts/getParticularsList/")
  //     .then(function(response) {
          
  //         if (response.data.status == 200) {
  //             console.log(response.data.particularsList);
  //         }
  //         hideOverlay();
  //     })
  //     .catch(function(error) {
  //         console.log(error);
  //     });
  // }
  
  
  
  });