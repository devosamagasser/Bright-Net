<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Category / Datasheet Builder</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background: #f4f5f7;
    }

    .container {
      max-width: 1100px;
      margin: 30px auto;
      padding: 20px 24px 40px;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    h1 {
      margin-top: 0;
      margin-bottom: 12px;
      font-size: 26px;
      font-weight: 700;
      color: #111827;
    }

    .subtitle {
      margin-top: 0;
      margin-bottom: 20px;
      color: #6b7280;
      font-size: 14px;
    }

    button {
      border: none;
      padding: 8px 14px;
      border-radius: 999px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 500;
      transition: transform 0.05s ease-out, box-shadow 0.1s ease-out, background 0.1s;
    }

    button:active {
      transform: scale(0.97);
      box-shadow: none;
    }

    .group-card {
      border-radius: 14px;
      border: 1px solid #e5e7eb;
      background: #f9fafb;
      padding: 16px 16px 18px;
      margin-bottom: 12px;
    }

    .group-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .group-header h2 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
      color: #111827;
    }

    .group-body {
      border-top: 1px dashed #e5e7eb;
      padding-top: 10px;
      margin-top: 6px;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 10px;
    }

    .field {
      flex: 1;
      min-width: 140px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .field.small {
      max-width: 130px;
      flex: 0 0 130px;
    }

    .field.full {
      flex: 1 0 100%;
    }

    label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #6b7280;
      font-weight: 600;
    }

    input[type="text"],
    input[type="number"],
    select,
    textarea {
      border-radius: 10px;
      border: 1px solid #e5e7eb;
      padding: 7px 9px;
      font-size: 13px;
      background: #ffffff;
      outline: none;
      transition: border-color 0.1s ease, box-shadow 0.1s ease, background 0.1s;
    }

    input[type="text"]:focus,
    input[type="number"]:focus,
    select:focus,
    textarea:focus {
      border-color: #111827;
      box-shadow: 0 0 0 1px #11182711;
      background: #f9fafb;
    }

    textarea {
      resize: vertical;
      min-height: 40px;
    }

    .checkbox-field {
      flex-direction: row;
      align-items: center;
      gap: 6px;
      margin-top: 16px;
      font-size: 12px;
      color: #374151;
    }

    .checkbox-field label {
      font-size: 12px;
      text-transform: none;
      letter-spacing: 0;
      font-weight: 500;
      color: #4b5563;
    }

    .checkbox-field input[type="checkbox"] {
      margin-right: 4px;
    }

    .datasheet-section {
      margin-top: 12px;
      padding: 10px 10px 10px;
      border-radius: 12px;
      background: #ffffff;
      border: 1px solid #e5e7eb;
    }

    .datasheet-section h3 {
      margin: 0 0 8px;
      font-size: 14px;
      font-weight: 600;
      color: #111827;
    }

    .add-field {
      margin-top: 6px;
      background: #0f766e;
      color: white;
      font-size: 12px;
      padding: 6px 12px;
      box-shadow: 0 6px 14px rgba(15, 118, 110, 0.35);
    }

    .add-field:hover {
      background: #115e59;
    }

    .field-row {
      margin-bottom: 10px;
      padding: 10px;
      border-radius: 10px;
      background: #f9fafb;
      border: 1px dashed #e5e7eb;
    }

    .field-row-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .field-row-title {
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: #9ca3af;
    }

    .remove-field {
      background: #f3f4f6;
      color: #6b7280;
      padding: 4px 9px;
      border-radius: 999px;
      font-size: 11px;
    }

    .remove-field:hover {
      background: #fee2e2;
      color: #b91c1c;
    }

    .options-section,
    .dependency-section {
      border-top: 1px dashed #e5e7eb;
      padding-top: 8px;
      margin-top: 6px;
    }

    .hidden {
      display: none;
    }

    .export-section {
      margin-top: 20px;
      border-top: 1px solid #e5e7eb;
      padding-top: 16px;
    }

    .export-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 10px;
    }

    #previewJson {
      background: #111827;
      color: white;
      box-shadow: 0 8px 18px rgba(15, 23, 42, 0.35);
    }

    #previewJson:hover {
      background: #020617;
    }

    #copyJson {
      background: #e5e7eb;
      color: #111827;
    }

    #copyJson:hover {
      background: #d1d5db;
    }

    #output {
      width: 100%;
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      padding: 10px;
      font-family: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,
        "Liberation Mono", "Courier New", monospace;
      font-size: 12px;
      background: #020617;
      color: #e5e7eb;
      min-height: 180px;
      white-space: pre;
    }

    .helper-text {
      font-size: 11px;
      color: #6b7280;
      margin-bottom: 6px;
    }

    .actions-row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 10px;
    }

    .btn-save {
      background: #2563eb;
      color: #ffffff;
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    }

    .btn-save:hover {
      background: #1d4ed8;
    }

    .btn-clear {
      background: #f3f4f6;
      color: #111827;
    }

    .btn-clear:hover {
      background: #e5e7eb;
    }

    @media (max-width: 768px) {
      .container {
        margin: 10px;
        padding: 14px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Category / Datasheet Builder</h1>
    <p class="subtitle">
      أدخل الـ Category، واختار الـ Subcategory (Indoor/Outdoor)، وبعدين اضيف الـ fields للـ Family و Product. اعمل Save علشان
      تنزل JSON، أو Clear علشان تبدأ من الأول.
    </p>

    <div class="group-card" id="group">
      <div class="group-header">
        <h2>Category / Subcategory</h2>
      </div>
      <div class="group-body">
        <!-- <div class="row">
          <div class="field">
            <label>Category</label>
            <input type="text" class="category-input" placeholder="e.g. Lighting, Furniture" />
          </div>
          <div class="field">
            <label>Subcategory</label>
            <select class="subcategory-input">
              <option value="Indoor">Indoor</option>
              <option value="Outdoor">Outdoor</option>
            </select>
          </div>
        </div> -->
        <div class="row">
            <div class="field">
                <label>Category</label>
                <select class="category-input">
                    <option value="Indoor">Indoor</option>
                    <option value="Outdoor">Outdoor</option>
                </select>
            </div>
            <div class="field">
                <label>Subcategory</label>
                <input type="text" class="subcategory-input" placeholder="e.g. Lighting, Garden Lights, Spotlights" />
            </div>
        </div>


        <div class="datasheet-section">
          <h3>Family Datasheet Fields</h3>
          <div class="fields-container family-fields"></div>
          <button type="button" class="add-field add-family-field">+ Add Family Field</button>
        </div>

        <div class="datasheet-section">
          <h3>Product Datasheet Fields</h3>
          <div class="fields-container product-fields"></div>
          <button type="button" class="add-field add-product-field">+ Add Product Field</button>
        </div>

        <div class="actions-row">
          <button type="button" class="btn-save save-subcategory">Save This Subcategory (Download JSON)</button>
          <button type="button" class="btn-clear clear-all">Clear</button>
        </div>
      </div>
    </div>

    <div class="export-section">
      <div class="helper-text">
        Preview / copy للـ JSON الحالي قبل ما تستخدمه في الـ seeding.
      </div>
      <div class="export-actions">
        <button id="previewJson" type="button">Preview JSON</button>
        <button id="copyJson" type="button">Copy JSON</button>
      </div>
      <textarea id="output" placeholder="JSON output will appear here..."></textarea>
    </div>
  </div>

  <script>
    const groupCard = document.getElementById("group");
    const familyContainer = groupCard.querySelector(".family-fields");
    const productContainer = groupCard.querySelector(".product-fields");
    const previewBtn = document.getElementById("previewJson");
    const copyBtn = document.getElementById("copyJson");
    const outputArea = document.getElementById("output");

    function createFieldRow(datasheetType) {
      const row = document.createElement("div");
      row.className = "field-row";
      row.dataset.datasheet = datasheetType;

      row.innerHTML = `
        <div class="field-row-header">
          <div class="field-row-title">
            ${datasheetType === "family" ? "Family field" : "Product field"}
          </div>
          <button type="button" class="remove-field">Remove</button>
        </div>
        <div class="row">
          <div class="field small">
            <label>Type</label>
            <select class="field-type">
              <option value="text">text</option>
              <option value="number">number</option>
              <option value="select">select</option>
            </select>
          </div>
          <div class="field">
            <label>Name (key)</label>
            <input type="text" class="field-name" placeholder="e.g. brand, model, color" />
          </div>
          <div class="field">
            <label>Label</label>
            <input type="text" class="field-label" placeholder="e.g. Brand, Model, Color" />
          </div>
          <div class="field small">
            <label>Position</label>
            <input type="number" class="field-position" min="0" />
          </div>
        </div>
        <div class="row">
          <div class="field">
            <label>Placeholder</label>
            <input type="text" class="field-placeholder" placeholder="Optional placeholder" />
          </div>
          <div class="field checkbox-field">
            <label>
              <input type="checkbox" class="field-required" />
              Required
            </label>
          </div>
          <div class="field checkbox-field">
            <label>
              <input type="checkbox" class="field-filterable" />
              Filterable
            </label>
          </div>
          <div class="field checkbox-field">
            <label>
              <input type="checkbox" class="field-is-depended" />
              Dependent field
            </label>
          </div>
        </div>
        <div class="options-section hidden">
          <div class="row">
            <div class="field full">
              <label>Options (comma separated)</label>
              <input type="text" class="field-options" placeholder="Small, Medium, Large" />
            </div>
          </div>
        </div>
        <div class="dependency-section hidden">
          <div class="row">
            <div class="field">
              <label>Depends on field</label>
              <input type="text" class="depends-on-field" placeholder="e.g. brand" />
            </div>
            <div class="field">
              <label>Depends on value</label>
              <input type="text" class="depends-on-value" placeholder="e.g. Apple" />
            </div>
          </div>
        </div>
      `;

      return row;
    }

    // add field buttons
    groupCard.addEventListener("click", (e) => {
      const target = e.target;

      if (target.classList.contains("add-family-field")) {
        familyContainer.appendChild(createFieldRow("family"));
        return;
      }

      if (target.classList.contains("add-product-field")) {
        productContainer.appendChild(createFieldRow("product"));
        return;
      }

      if (target.classList.contains("remove-field")) {
        const row = target.closest(".field-row");
        if (row) row.remove();
        return;
      }

      if (target.classList.contains("save-subcategory")) {
        const jsonObj = collectCurrentConfig();
        if (!jsonObj) return;

        const { category, subcategory } = jsonObj;
        const fileName = `${category || "Category"}-${subcategory || "Subcategory"}.json`.replace(/\s+/g, "_");

        const blob = new Blob([JSON.stringify(jsonObj, null, 2)], {
          type: "application/json",
        });

        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = fileName;
        link.click();
        return;
      }

      if (target.classList.contains("clear-all")) {
        clearAll();
        return;
      }
    });

    // type / dependency toggles
    groupCard.addEventListener("change", (e) => {
      const target = e.target;

      if (target.classList.contains("field-type")) {
        const row = target.closest(".field-row");
        if (!row) return;
        const optionsSection = row.querySelector(".options-section");
        if (target.value === "select") {
          optionsSection.classList.remove("hidden");
        } else {
          optionsSection.classList.add("hidden");
        }
      }

      if (target.classList.contains("field-is-depended")) {
        const row = target.closest(".field-row");
        if (!row) return;
        const depSection = row.querySelector(".dependency-section");
        if (target.checked) {
          depSection.classList.remove("hidden");
        } else {
          depSection.classList.add("hidden");
        }
      }
    });

    function collectCurrentConfig() {
      const categoryInput = groupCard.querySelector(".category-input");
      const subcategoryInput = groupCard.querySelector(".subcategory-input");

      const category = categoryInput?.value.trim() || "";
      const subcategory = subcategoryInput?.value || "";

      const familyFields = [];
      const productFields = [];

      const fieldRows = groupCard.querySelectorAll(".field-row");

      fieldRows.forEach((row) => {
        const datasheetType = row.dataset.datasheet;

        const type = row.querySelector(".field-type")?.value || "text";
        const name = row.querySelector(".field-name")?.value.trim() || "";
        const label = row.querySelector(".field-label")?.value.trim() || "";
        const placeholder = row.querySelector(".field-placeholder")?.value.trim() || "";
        const positionRaw = row.querySelector(".field-position")?.value;
        const position = positionRaw ? parseInt(positionRaw, 10) : null;
        const is_required = row.querySelector(".field-required")?.checked || false;
        const is_filterable = row.querySelector(".field-filterable")?.checked || false;
        const is_depended = row.querySelector(".field-is-depended")?.checked || false;

        let options = [];
        if (type === "select") {
          const rawOptions = row.querySelector(".field-options")?.value || "";
          options = rawOptions
            .split(",")
            .map((o) => o.trim())
            .filter((o) => o.length > 0);
        }

        let depends_on_field = null;
        let depends_on_value = null;
        if (is_depended) {
          depends_on_field = row.querySelector(".depends-on-field")?.value.trim() || null;
          depends_on_value = row.querySelector(".depends-on-value")?.value.trim() || null;
        }

        // تجاهل الـ fields الفاضية جدًا
        if (!name && !label) return;

        const fieldObj = {
          type,
          is_required,
          is_filterable,
          position,
          name,
          label,
          placeholder,
        };

        if (type === "select") {
          fieldObj.options = options;
        }

        if (is_depended) {
          fieldObj.is_depended = true;
          fieldObj.depends_on_field = depends_on_field;
          fieldObj.depends_on_value = depends_on_value;
        }

        if (datasheetType === "family") {
          familyFields.push(fieldObj);
        } else if (datasheetType === "product") {
          productFields.push(fieldObj);
        }
      });

      return {
        category,
        subcategory,
        family_datasheet: familyFields,
        product_datasheet: productFields,
      };
    }

    function clearAll() {
      const categoryInput = groupCard.querySelector(".category-input");
      const subcategoryInput = groupCard.querySelector(".subcategory-input");

      if (categoryInput) categoryInput.value = "";
      if (subcategoryInput) subcategoryInput.value = "Indoor";

      familyContainer.innerHTML = "";
      productContainer.innerHTML = "";
      outputArea.value = "";
    }

    // Preview JSON
    previewBtn.addEventListener("click", () => {
      const jsonObj = collectCurrentConfig();
      outputArea.value = JSON.stringify(jsonObj, null, 2);
    });

    // Copy JSON
    copyBtn.addEventListener("click", async () => {
      const text = outputArea.value;
      if (!text) return;

      try {
        if (navigator.clipboard && navigator.clipboard.writeText) {
          await navigator.clipboard.writeText(text);
          alert("JSON copied to clipboard");
        } else {
          outputArea.select();
          document.execCommand("copy");
          alert("JSON copied (execCommand)");
        }
      } catch (err) {
        console.error(err);
        alert("Unable to copy JSON");
      }
    });
  </script>
</body>
</html>
