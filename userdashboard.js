const category = document.getElementById("category");
const subCategory = document.getElementById("subCategory");

// Sub-categories for each category
const subCategories = {
  electricity: [
    "Transmitter Burst",
    "Cut Off Wires",
    "Pole Fall",
    "Half-cut Electricity",
  ],
  water: ["Dirty Water", "No Scheduled Time Water", "Pipe Burst"],
  garbage: ["No Scheduled Time", "Overload of Garbage"],
};

// Update sub-category dropdown when a category is selected
category.addEventListener("change", () => {
  const selectedCategory = category.value;

  // Clear the existing options
  subCategory.innerHTML = '<option value="">Select category</option>';

  if (selectedCategory && subCategories[selectedCategory]) {
    // Populate sub-category options
    subCategories[selectedCategory].forEach((item) => {
      const option = document.createElement("option");
      option.value = item.toLowerCase().replace(/ /g, "-");
      option.textContent = item;
      subCategory.appendChild(option);
    });
  }
});

// Handle form submission
const grievanceForm = document.getElementById("grievanceForm");
grievanceForm.addEventListener("submit", (event) => {
  const selectedCategory = category.value;
  const selectedSubCategory = subCategory.value;
  const description = document.getElementById("description").value;

  if (!selectedCategory || !selectedSubCategory || !description) {
    alert("Please fill all required fields!");
    event.preventDefault();
    return;
  }
});
