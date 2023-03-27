function searchTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("bookTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those that don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0]; // Search only on the first column (name)
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}


window.addEventListener("load", function () {
  // Get edit buttons
  var editButtons = document.querySelectorAll(".editButton");

  // Add click event listener to each edit button
  editButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      // Get book data from row
      var row = this.closest("tr");
      var id = row.getAttribute("data-id");
      var name = row.querySelector("td:nth-child(1)").textContent;
      var author = row.querySelector("td:nth-child(2)").textContent;
      var description = row.querySelector("td:nth-child(3)").textContent;
      var availability = row.querySelector("td:nth-child(4)").textContent;

      // Populate modal fields
      document.getElementById("edit-id").value = id;
      document.getElementById("edit-name").value = name;
      document.getElementById("edit-author").value = author;
      document.getElementById("edit-description").value = description;
      document.getElementById("edit-availability").value = availability;

      // Show modal
      var editModal = new bootstrap.Modal(document.getElementById("editModal"));
      editModal.show();

      // Get form element
      var formElement = document.querySelector("#editModal form");

      // Add submit event listener to form
      formElement.addEventListener("submit", function (event) {
        // Prevent default form submission
        event.preventDefault();

        // Get form data
        var id = document.querySelector("#edit-id").value;
        var name = document.querySelector("#edit-name").value;
        var author = document.querySelector("#edit-author").value;
        var description = document.querySelector("#edit-description").value;
        var availability = document.querySelector("#edit-availability").value;

        // Find row with matching id
        var row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
          // Update row data
          row.querySelector("td:nth-child(1)").textContent = name;
          row.querySelector("td:nth-child(2)").textContent = author;
          row.querySelector("td:nth-child(3)").textContent = description;
          row.querySelector("td:nth-child(4)").textContent = availability;
        }

        // Hide modal
        editModal.hide();
      });
    });
  });
  // Get delete buttons
  var deleteButtons = document.querySelectorAll(".btn-danger");

  // Add click event listener to each delete button
  deleteButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      // Get book data from row
      var row = this.closest("tr");

      // Remove row from table
      row.parentNode.removeChild(row);
    });
  });
});
