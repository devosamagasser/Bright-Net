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
      max-width: 1200px;
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
      margin-bottom: 20px;
    }

    .group-header {
      margin-bottom: 10px;
    }

    .group-header h2 {
      margin: 0;
      font-size: 18px;
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

    .datasheets-wrapper {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      margin-top: 12px;
    }

    .datasheet-column {
      flex: 1 1 0;
      min-width: 260px;
    }

    .datasheet-section {
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
      margin-bottom: 6px;
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

    .actions-row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 14px;
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

    .btn-preview {
      background: #111827;
      color: #ffffff;
      box-shadow: 0 6px 16px rgba(15, 23, 42, 0.35);
    }

    .btn-preview:hover {
      background: #020617;
    }

    .preview-container {
      margin-top: 24px;
      border-top: 1px solid #e5e7eb;
      padding-top: 16px;
    }

    .preview-title {
      font-size: 15px;
      font-weight: 600;
      color: #111827;
      margin-bottom: 8px;
    }

    .preview-columns {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
    }

    .preview-column {
      flex: 1 1 0;
      min-width: 260px;
    }

    .preview-box {
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      background: #f9fafb;
      padding: 10px 12px;
    }

    .preview-empty {
      font-size: 12px;
      color: #9ca3af;
      font-style: italic;
    }

    details.preview-details {
      margin-bottom: 6px;
      border-radius: 10px;
      background: #ffffff;
      border: 1px solid #e5e7eb;
      padding: 6px 8px 8px;
    }

    details.preview-details > summary {
      cursor: pointer;
      list-style: none;
      font-size: 13px;
      font-weight: 600;
      color: #111827;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    details.preview-details > summary::-webkit-details-marker {
      display: none;
    }

    .summary-label {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .summary-tag {
      font-size: 10px;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 2px 6px;
      border-radius: 999px;
      background: #eff6ff;
      color: #1d4ed8;
    }

    .preview-fields {
      margin-top: 8px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .preview-field {
      border-radius: 8px;
      border: 1px dashed #e5e7eb;
      background: #f9fafb;
      padding: 6px 8px 8px;
    }

    .preview-field-label {
      font-size: 12px;
      font-weight: 600;
      color: #111827;
      margin-bottom: 4px;
    }

    .preview-field-meta {
      font-size: 11px;
      color: #6b7280;
      margin-bottom: 4px;
    }

    .preview-field-control {
      margin-bottom: 4px;
    }

    .preview-field small {
      font-size: 11px;
      color: #6b7280;
    }

    .preview-input,
    .preview-select {
      width: 100%;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      padding: 6px 8px;
      font-size: 12px;
      background: #ffffff;
    }

    .preview-select[multiple] {
      min-height: 60px;
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
      اختار الـ Category (Indoor / Outdoor)، اكتب الـ Subcategory، وبعدين اضيف الـ fields للـ Family & Product. تقدر تحفظ
      كـ JSON وتعمل Preview للشكل الفعلي للحقول.
    </p>

    <div class="group-card" id="group">
      <div class="group-header">
        <h2>Category / Subcategory</h2>
      </div>
      <div class="group-body">
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
            <input type="text" class="subcategory-input" placeholder="e.g. Lighting, Garden Lights" />
          </div>
        </div>

        <div class="datasheets-wrapper">
          <div class="datasheet-column">
            <div class="datasheet-section">
              <h3>Family Datasheet Fields</h3>
              <div class="fields-container family-fields"></div>
              <button type="button" class="add-field add-family-field">+ Add Family Field</button>
            </div>
          </div>

          <div class="datasheet-column">
            <div class="datasheet-section">
              <h3>Product Datasheet Fields</h3>
              <div class="fields-container product-fields"></div>
              <button type="button" class="add-field add-product-field">+ Add Product Field</button>
            </div>
          </div>
        </div>

        <div class="actions-row">
          <button type="button" class="btn-save save-subcategory">Save (Download JSON)</button>
          <button type="button" class="btn-clear clear-all">Clear</button>
          <button type="button" class="btn-preview preview-btn">Update Preview</button>
        </div>
      </div>
    </div>

    <div class="preview-container">
      <div class="preview-title">Preview (View Mode)</div>
      <div class="preview-columns">
        <div class="preview-column">
          <h3 style="margin:0 0 6px;font-size:14px;color:#111827;">Family Preview</h3>
          <div id="familyPreview" class="preview-box">
            <div class="preview-empty">No family fields yet.</div>
          </div>
        </div>
        <div class="preview-column">
          <h3 style="margin:0 0 6px;font-size:14px;color:#111827;">Product Preview</h3>
          <div id="productPreview" class="preview-box">
            <div class="preview-empty">No product fields yet.</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const groupCard = document.getElementById("group");
    const familyContainer = groupCard.querySelector(".family-fields");
    const productContainer = groupCard.querySelector(".product-fields");
    const familyPreviewBox = document.getElementById("familyPreview");
    const productPreviewBox = document.getElementById("productPreview");

    function labelToName(label) {
      if (!label) return null;
      return label
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s\-_]/g, "")
        .replace(/\s+/g, "_");
    }

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
              <option value="multiselect">multiselect</option>
            </select>
          </div>
          <div class="field">
            <label>Label</label>
            <input type="text" class="field-label" placeholder="e.g. Brand, Wattage, Color" />
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
              <label>Depends on values</label>
              <input type="text" class="depends-on-value" placeholder="Apple, Samsung, Xiaomi" />
            </div>
          </div>
        </div>
      `;

      return row;
    }

    // click handlers: add/remove/save/clear/preview
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
        const { category, subcategory } = jsonObj;

        const fileName = `${category || "Category"}-${subcategory || "Subcategory"}.json`.replace(
          /\s+/g,
          "_"
        );

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

      if (target.classList.contains("preview-btn")) {
        updatePreview();
        return;
      }
    });

    // change handlers: type options / dependency toggle
    groupCard.addEventListener("change", (e) => {
      const target = e.target;

      if (target.classList.contains("field-type")) {
        const row = target.closest(".field-row");
        if (!row) return;
        const optionsSection = row.querySelector(".options-section");
        if (target.value === "select" || target.value === "multiselect") {
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

      const category = categoryInput?.value || "";
      const subcategory = subcategoryInput?.value.trim() || "";

      const familyFields = [];
      const productFields = [];

      const fieldRows = groupCard.querySelectorAll(".field-row");

      fieldRows.forEach((row) => {
        const datasheetType = row.dataset.datasheet;

        const type = row.querySelector(".field-type")?.value || "text";
        const label = row.querySelector(".field-label")?.value.trim() || "";
        const placeholder = row.querySelector(".field-placeholder")?.value.trim() || "";
        const positionRaw = row.querySelector(".field-position")?.value;
        const position = positionRaw ? parseInt(positionRaw, 10) : null;
        const is_required = row.querySelector(".field-required")?.checked || false;
        const is_filterable = row.querySelector(".field-filterable")?.checked || false;
        const is_depended = row.querySelector(".field-is-depended")?.checked || false;

        if (!label) return;
        const name = labelToName(label);
        if (!name) return;

        let options = [];
        if (type === "select" || type === "multiselect") {
          const rawOptions = row.querySelector(".field-options")?.value || "";
          options = rawOptions
            .split(",")
            .map((o) => o.trim())
            .filter((o) => o.length > 0);
        }

        let depends_on_field = null;
        let depends_on_values = [];
        if (is_depended) {
          depends_on_field = row.querySelector(".depends-on-field")?.value.trim() || null;
          const depends_on_value_raw = row.querySelector(".depends-on-value")?.value.trim() || "";
          depends_on_values = depends_on_value_raw
            .split(",")
            .map((v) => v.trim())
            .filter((v) => v.length > 0);
        }

        const fieldObj = {
          type,
          name,
          label,
          placeholder,
          position,
          is_required,
          is_filterable,
        };

        if (type === "select" || type === "multiselect") {
          fieldObj.options = options;
        }

        if (is_depended) {
          fieldObj.is_depended = true;
          fieldObj.depends_on_field = depends_on_field;
          fieldObj.depends_on_values = depends_on_values;
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

      if (categoryInput) categoryInput.value = "Indoor";
      if (subcategoryInput) subcategoryInput.value = "";

      familyContainer.innerHTML = "";
      productContainer.innerHTML = "";
      familyPreviewBox.innerHTML = `<div class="preview-empty">No family fields yet.</div>`;
      productPreviewBox.innerHTML = `<div class="preview-empty">No product fields yet.</div>`;
    }

    function updatePreview() {
      const config = collectCurrentConfig();
      renderPreviewSection(
        familyPreviewBox,
        config.subcategory,
        config.family_datasheet,
        "Family Datasheet"
      );
      renderPreviewSection(
        productPreviewBox,
        config.subcategory,
        config.product_datasheet,
        "Product Datasheet"
      );
    }

    function renderPreviewSection(container, subcategory, fields, titleLabel) {
      container.innerHTML = "";

      if (!fields || fields.length === 0) {
        container.innerHTML = `<div class="preview-empty">No fields yet.</div>`;
        return;
      }

      const details = document.createElement("details");
      details.className = "preview-details";
      details.open = true;

      const summary = document.createElement("summary");
      const summaryLabel = document.createElement("div");
      summaryLabel.className = "summary-label";
      const textSpan = document.createElement("span");
      textSpan.textContent = `${titleLabel} — ${subcategory || "No subcategory"}`;
      const tagSpan = document.createElement("span");
      tagSpan.className = "summary-tag";
      tagSpan.textContent = "Preview";
      summaryLabel.appendChild(textSpan);
      summaryLabel.appendChild(tagSpan);
      summary.appendChild(summaryLabel);

      details.appendChild(summary);

      const content = document.createElement("div");
      content.className = "preview-fields";

      fields
        .slice()
        .sort((a, b) => {
          const pa = a.position ?? 0;
          const pb = b.position ?? 0;
          return pa - pb;
        })
        .forEach((field) => {
          const fieldWrapper = document.createElement("div");
          fieldWrapper.className = "preview-field";

          const labelEl = document.createElement("div");
          labelEl.className = "preview-field-label";
          labelEl.textContent = field.label + (field.is_required ? " *" : "");

          const metaEl = document.createElement("div");
          metaEl.className = "preview-field-meta";
          metaEl.textContent = `type: ${field.type}${
            field.is_filterable ? " · filterable" : ""
          } · name: ${field.name}${field.position != null ? " · position: " + field.position : ""}`;

          const controlWrapper = document.createElement("div");
          controlWrapper.className = "preview-field-control";

          let control;
          if (field.type === "text" || field.type === "number") {
            control = document.createElement("input");
            control.type = field.type === "number" ? "number" : "text";
            control.className = "preview-input";
            control.placeholder = field.placeholder || "";
            control.disabled = true;
          } else if (field.type === "select" || field.type === "multiselect") {
            control = document.createElement("select");
            control.className = "preview-select";
            if (field.type === "multiselect") {
              control.multiple = true;
            }
            (field.options || []).forEach((opt) => {
              const o = document.createElement("option");
              o.value = opt;
              o.textContent = opt;
              control.appendChild(o);
            });
            control.disabled = true;
          } else {
            control = document.createElement("input");
            control.type = "text";
            control.className = "preview-input";
            control.disabled = true;
          }

          controlWrapper.appendChild(control);

          fieldWrapper.appendChild(labelEl);
          fieldWrapper.appendChild(metaEl);
          fieldWrapper.appendChild(controlWrapper);

          if (field.is_depended && field.depends_on_field) {
            const depInfo = document.createElement("div");
            depInfo.innerHTML = `<small>Depends on <strong>${
              field.depends_on_field
            }</strong> ∈ [${(field.depends_on_values || []).join(", ")}]</small>`;
            fieldWrapper.appendChild(depInfo);
          }

          content.appendChild(fieldWrapper);
        });

      details.appendChild(content);
      container.appendChild(details);
    }

    // init: start with no fields, previews empty
    clearAll();
  </script>
</body>
</html>
