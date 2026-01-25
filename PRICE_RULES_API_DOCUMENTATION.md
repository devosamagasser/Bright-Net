# Price Rules API Documentation

## نظرة عامة

نظام Price Rules يتيح إدارة عوامل التحويل بين العملات وتطبيق عوامل التسعير على المنتجات.

## Base URL
```
/api/suppliers/{supplier_id}
```

---

## 1. Currency Transform Factors (عوامل التحويل بين العملات)

### 1.1 عرض جميع عوامل التحويل
```http
GET /api/suppliers/{supplier_id}/currency-transform-factors
```

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "supplier_id": 1,
      "from": "USD",
      "to": "EGP",
      "factor": 50.00,
      "created_at": "2026-01-25 10:00:00",
      "updated_at": "2026-01-25 10:00:00"
    },
    {
      "id": 2,
      "supplier_id": 1,
      "from": "USD",
      "to": "EUR",
      "factor": 0.92,
      "created_at": "2026-01-25 10:00:00",
      "updated_at": "2026-01-25 10:00:00"
    }
  ]
}
```

---

### 1.2 إضافة عامل تحويل جديد
```http
POST /api/suppliers/{supplier_id}/currency-transform-factors
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "from": "USD",
  "to": "EGP",
  "factor": 50.00
}
```

**Validation Rules:**
- `from`: required, string, must be valid currency (USD, EGP, EUR, SR)
- `to`: required, string, must be valid currency, must be different from `from`
- `factor`: required, numeric, min: 0.0001

**Response:**
```json
{
  "success": true,
  "message": "Created successfully",
  "data": {
    "id": 1,
    "supplier_id": 1,
    "from": "USD",
    "to": "EGP",
    "factor": 50.00,
    "created_at": "2026-01-25 10:00:00",
    "updated_at": "2026-01-25 10:00:00"
  }
}
```

---

### 1.3 تحديث عامل تحويل
```http
PUT /api/suppliers/{supplier_id}/currency-transform-factors/{currency_transform_factor_id}
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "factor": 51.00
}
```

**Validation Rules:**
- `factor`: required, numeric, min: 0.0001

**Response:**
```json
{
  "success": true,
  "message": "Updated successfully",
  "data": {
    "id": 1,
    "supplier_id": 1,
    "from": "USD",
    "to": "EGP",
    "factor": 51.00,
    "created_at": "2026-01-25 10:00:00",
    "updated_at": "2026-01-25 11:00:00"
  }
}
```

---

### 1.4 حذف عامل تحويل
```http
DELETE /api/suppliers/{supplier_id}/currency-transform-factors/{currency_transform_factor_id}
```

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Deleted successfully"
}
```

---

## 2. Price Factors (عوامل التسعير)

### 2.1 تطبيق عامل تسعير على منتجات
```http
POST /api/suppliers/{supplier_id}/price-factors/apply
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "product_ids": [1, 2, 3, 4, 5],
  "factor": 1.15,
  "notes": "زيادة سعر 15% بسبب ارتفاع التكاليف"
}
```

**Validation Rules:**
- `product_ids`: required, array, min: 1 item
- `product_ids.*`: required, integer, must exist in products table
- `factor`: required, numeric, min: 0.0001
- `notes`: optional, string, max: 1000

**ملاحظات:**
- يتم تطبيق الـ factor على **جميع** أسعار المنتجات المحددة (كل quantity ranges)
- الـ factors متراكمة (cumulative) - إذا كان هناك factor سابق 1.1 وتم تطبيق 1.15، النتيجة = 1.1 × 1.15 = 1.265

**Response:**
```json
{
  "success": true,
  "message": "Created successfully",
  "data": {
    "id": 1,
    "supplier_id": 1,
    "user_id": 5,
    "factor": 1.15,
    "status": "active",
    "status_label": "Active",
    "parent_factor_id": null,
    "notes": "زيادة سعر 15% بسبب ارتفاع التكاليف",
    "user": {
      "id": 5,
      "name": "Ahmed Mohamed"
    },
    "parent_factor": null,
    "created_at": "2026-01-25 10:00:00",
    "updated_at": "2026-01-25 10:00:00"
  }
}
```

---

### 2.2 التراجع عن عامل تسعير
```http
POST /api/suppliers/{supplier_id}/price-factors/{factor_id}/revert
```

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Updated successfully",
  "data": {
    "id": 1,
    "supplier_id": 1,
    "user_id": 5,
    "factor": 1.15,
    "status": "reverted",
    "status_label": "Reverted",
    "parent_factor_id": null,
    "notes": "زيادة سعر 15% بسبب ارتفاع التكاليف",
    "user": {
      "id": 5,
      "name": "Ahmed Mohamed"
    },
    "created_at": "2026-01-25 10:00:00",
    "updated_at": "2026-01-25 11:00:00"
  }
}
```

---

### 2.3 إعادة تطبيق عامل تسعير
```http
POST /api/suppliers/{supplier_id}/price-factors/{factor_id}/reapply
```

**Headers:**
```
Authorization: Bearer {token}
```

**ملاحظات:**
- ينشئ factor جديد بنفس القيمة والمنتجات
- الـ `parent_factor_id` يشير للـ factor الأصلي

**Response:**
```json
{
  "success": true,
  "message": "Created successfully",
  "data": {
    "id": 2,
    "supplier_id": 1,
    "user_id": 5,
    "factor": 1.15,
    "status": "active",
    "status_label": "Active",
    "parent_factor_id": 1,
    "notes": "زيادة سعر 15% بسبب ارتفاع التكاليف",
    "user": {
      "id": 5,
      "name": "Ahmed Mohamed"
    },
    "parent_factor": {
      "id": 1,
      "factor": 1.15
    },
    "created_at": "2026-01-25 12:00:00",
    "updated_at": "2026-01-25 12:00:00"
  }
}
```

---

### 2.4 عرض تاريخ عوامل التسعير
```http
GET /api/suppliers/{supplier_id}/price-factors/history?per_page=15
```

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `per_page`: optional, integer, default: 15, min: 1, max: 100

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "supplier_id": 1,
        "user_id": 5,
        "factor": 1.15,
        "status": "active",
        "status_label": "Active",
        "parent_factor_id": 1,
        "notes": "زيادة سعر 15%",
        "user": {
          "id": 5,
          "name": "Ahmed Mohamed"
        },
        "parent_factor": {
          "id": 1,
          "factor": 1.15
        },
        "products": [
          {
            "id": 1,
            "code": "PROD-001",
            "name": "Product Name"
          },
          {
            "id": 2,
            "code": "PROD-002",
            "name": "Product Name 2"
          }
        ],
        "created_at": "2026-01-25 12:00:00",
        "updated_at": "2026-01-25 12:00:00"
      }
    ],
    "total": 10,
    "per_page": 15,
    "last_page": 1
  }
}
```

---

### 2.5 عرض المنتجات المتأثرة بعامل تسعير
```http
GET /api/suppliers/{supplier_id}/price-factors/{factor_id}/products
```

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "code": "PROD-001",
      "name": "Product Name",
      "description": "Product Description",
      "stock": 100,
      "prices": [...],
      "values": [...],
      "media": {...}
    },
    {
      "id": 2,
      "code": "PROD-002",
      "name": "Product Name 2",
      ...
    }
  ]
}
```

---

## 3. Products Integration (تكامل مع المنتجات)

### 3.1 عرض المنتجات مع الأسعار المعدلة
```http
GET /api/suppliers/{supplier_id}/products/prices?currency=EGP&per_page=15
```

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `currency`: optional, string, default: "USD" (USD, EGP, EUR, SR)
- `per_page`: optional, integer, default: 15

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "code": "PROD-001",
        "name": "Product Name",
        "prices": [
          {
            "id": 1,
            "price": 100.00,
            "from": 1,
            "to": 10,
            "currency": "USD",
            "delivery_time_unit": "days",
            "delivery_time_value": "7",
            "vat_status": true,
            "calculated_price": {
              "original_price": 100.00,
              "original_currency": "USD",
              "converted_price": 5000.00,
              "final_price": 5750.00,
              "currency": "EGP",
              "factors_applied": [
                {
                  "id": 1,
                  "factor": 1.15,
                  "applied_at": "2026-01-25 10:00:00"
                }
              ]
            }
          }
        ]
      }
    ]
  }
}
```

**ملاحظات:**
- `calculated_price` يظهر فقط إذا تم تمرير `currency` parameter
- الحساب يتم كالتالي:
  1. تحويل العملة أولاً (USD → EGP: 100 × 50 = 5000)
  2. ثم تطبيق كل الـ factors النشطة (5000 × 1.15 = 5750)

---

### 3.2 عرض منتج واحد مع السعر المعدل
```http
GET /api/products/{product_id}?currency=EGP
```

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `currency`: optional, string (USD, EGP, EUR, SR)

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "code": "PROD-001",
    "name": "Product Name",
    "prices": [
      {
        "id": 1,
        "price": 100.00,
        "currency": "USD",
        "calculated_price": {
          "original_price": 100.00,
          "original_currency": "USD",
          "converted_price": 5000.00,
          "final_price": 5750.00,
          "currency": "EGP",
          "factors_applied": [
            {
              "id": 1,
              "factor": 1.15,
              "applied_at": "2026-01-25 10:00:00"
            }
          ]
        }
      }
    ]
  }
}
```

---

## أمثلة على الاستخدام

### مثال 1: إضافة عامل تحويل عملة
```bash
curl -X POST "https://api.example.com/api/suppliers/1/currency-transform-factors" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "from": "USD",
    "to": "EGP",
    "factor": 50.00
  }'
```

### مثال 2: تطبيق عامل تسعير على منتجات
```bash
curl -X POST "https://api.example.com/api/suppliers/1/price-factors/apply" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "product_ids": [1, 2, 3],
    "factor": 1.20,
    "notes": "زيادة 20% بسبب ارتفاع التكاليف"
  }'
```

### مثال 3: عرض المنتجات بالجنيه المصري
```bash
curl -X GET "https://api.example.com/api/suppliers/1/products/prices?currency=EGP" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## حالات الاستخدام الشائعة

### سيناريو 1: إضافة عوامل تحويل عملات
1. إضافة USD → EGP: `factor = 50`
2. إضافة USD → EUR: `factor = 0.92`
3. إضافة EGP → USD: `factor = 0.02` (1/50)

### سيناريو 2: تطبيق زيادة سعر على منتجات محددة
1. تطبيق factor 1.15 على منتجات [1, 2, 3]
2. تطبيق factor 1.10 على نفس المنتجات (النتيجة: 1.15 × 1.10 = 1.265)
3. التراجع عن factor الأول → النتيجة: 1.10 فقط

### سيناريو 3: عرض السعر النهائي
- المنتج سعره الأصلي: 100 USD
- عامل تحويل USD → EGP: 50
- عوامل تسعير: 1.15, 1.10
- السعر النهائي بالجنيه: 100 × 50 × 1.15 × 1.10 = 6,325 EGP

---

## ملاحظات مهمة

1. **Factors متراكمة**: كل factor جديد يضرب في السعر بعد تطبيق السابق
2. **تحويل العملة أولاً**: يتم تحويل العملة أولاً ثم تطبيق factors
3. **السعر الأصلي محفوظ**: السعر الأصلي في قاعدة البيانات لا يتغير
4. **History كامل**: كل تعديل يتم تتبعه مع المستخدم والمنتجات المتأثرة
5. **إعادة التطبيق**: يمكن إعادة تطبيق factor تم التراجع عنه

