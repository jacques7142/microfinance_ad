# 🗺️ Système de Localisation et Détails des Agences

## ✅ Implémentation Complète

### **Fonctionnalités:**
1. ✅ Clic sur une carte d'agence → **Modal rapide** avec informations
2. ✅ Lien "Voir les détails complets" → **Page détails complète**
3. ✅ **Carte Leaflet** intégrée
4. ✅ **Coordonnées GPS** des agences
5. ✅ **Chef d'agence** (gérant) avec contact
6. ✅ **Horaires de fonctionnement**
7. ✅ **Mode de contact** (email, téléphone)
8. ✅ **Statistiques** (sociétaires, transactions)

---

## 📍 Routes Créées

```
GET  /agences/{id}         → Page détails complète de l'agence
GET  /api/agence/{id}      → API JSON pour le modal
```

---

## 🏗️ Structure Implémentée

### **1. Nouveau Contrôleur**
- `app/Http/Controllers/AgenceDetailController.php`
  - `show()` - Page détails
  - `modal()` - API JSON pour modal

### **2. Nouvelles Vues**
- `resources/views/agences/detail.blade.php` - Page complète
- `resources/views/partials/agence-modal.blade.php` - Modal popup

### **3. Migration**
- `database/migrations/2026_07_22_000002_*.php`
  - Ajout champs: `latitude`, `longitude`, `secteur`
  - Ajout champs: `telephone_agence`, `description`
  - Ajout champs: `horaires_fonctionnement`, `actif`

### **4. Modèle Agence**
Mise à jour du modèle avec:
- Nouvelle méthode: `gerant()` - Récupère le chef d'agence
- Nouveaux champs fillable
- Casts pour JSON et floats

### **5. Routes**
Ajoutées à `routes/web.php`:
```php
Route::get('/agences/{agence}', [AgenceDetailController::class, 'show'])->name('agences.show');
Route::get('/api/agence/{agence}', [AgenceDetailController::class, 'modal'])->name('api.agence.modal');
```

### **6. Dashboard Admin**
- Cartes d'agences **cliquables**
- `onclick="openAgenceModal(agenceId)"`
- Include du modal

---

## 🗺️ Carte Intégrée

**Bibliothèque:** Leaflet.js (open-source)
- ✅ Support des tuiles OpenStreetMap (gratuit)
- ✅ Marqueurs avec popups
- ✅ Cercles de rayon
- ✅ Zoom/Pan interactif

**URL Référence:** https://unpkg.com/leaflet@1.9.4

---

## 💾 Données Agences

### **Champs Ajoutés:**

| Champ | Type | Description |
|-------|------|-------------|
| `latitude` | decimal(10,8) | Coordonnée GPS latitude |
| `longitude` | decimal(11,8) | Coordonnée GPS longitude |
| `secteur` | string | Région/Secteur de l'agence |
| `telephone_agence` | string | Numéro principal agence |
| `description` | text | Description/présentation |
| `horaires_fonctionnement` | json | Horaires lun-dim |
| `actif` | boolean | Statut opérationnel |

---

## 👥 Chef d'Agence

**Récupération:** Par relation `User::where('role', 'gerant')`

**Affichage:**
- Nom complet
- Email (avec lien mailto)
- Téléphone (avec lien tel)
- Avatar avec initiales

---

## 📲 Mode de Fonctionnement

**Horaires JSON stockés:**
```json
{
  "lundi": { "ouverture": "08:00", "fermeture": "16:00" },
  "mardi": { "ouverture": "08:00", "fermeture": "16:00" },
  ...
  "dimanche": { "ouverture": "Fermé", "fermeture": "Fermé" }
}
```

**Affichage:** Tableau formaté sur page détails

---

## 🎯 Flux Utilisateur (Admin)

### **Tableau de Bord:**
1. Admin voit **6 cartes d'agences**
2. Clique sur une carte → **Modal popup**
3. Modal affiche:
   - Carte miniature Leaflet
   - Infos agence (adresse, secteur)
   - Chef d'agence + contacts
   - Boutons: Email, Téléphone
   - Lien "Voir les détails complets"

### **Page Détails Complète:**
1. Vue large avec carte complète
2. Informations détaillées
3. Chef d'agence (gérant)
4. Horaires de fonctionnement
5. Statistiques (sociétaires, transactions)
6. Actions (appel, email, Google Maps)

---

## 🔧 Configuration pour Production

### **1. Remplir les Coordonnées GPS**

Via le seeder `AgenceLocationSeeder`:
```bash
php artisan db:seed --class=AgenceLocationSeeder
```

Ou manuellement via admin panel (futur développement).

### **2. Ajouter les Horaires**

```sql
UPDATE agences SET horaires_fonctionnement = JSON_OBJECT(
  'lundi', JSON_OBJECT('ouverture', '08:00', 'fermeture', '16:00'),
  'mardi', JSON_OBJECT('ouverture', '08:00', 'fermeture', '16:00'),
  ...
);
```

### **3. Assigner les Gérants**

S'assurer que chaque agence a un user avec rôle `gerant`.

---

## 🎨 UI/UX

**Modal:**
- Ouverture/fermeture fluide
- Fermeture par ESC ou clic en dehors
- Responsive (mobile-optimisé)
- Transitions CSS smooth

**Page Détails:**
- Design 2 colonnes (desktop)
- 1 colonne (mobile)
- Carte fullsize
- Contact buttons prominents

---

## 📊 Exemple de Données Seedées

```php
'Agence Kara' => [
    'latitude' => 9.5632,
    'longitude' => 1.2583,
    'secteur' => 'Kara',
    'description' => 'Agence régionale de Kara...',
    'telephone_agence' => '+228 XXX-XXXX',
    'horaires_fonctionnement' => [
        'lundi' => ['ouverture' => '08:00', 'fermeture' => '16:00'],
        ...
    ],
],
```

---

## ✅ Checklist Technique

- ✅ Migration exécutée
- ✅ Modèle Agence mis à jour
- ✅ Contrôleur créé
- ✅ Vues créées
- ✅ Routes configurées
- ✅ Modal intégré
- ✅ Leaflet/JavaScript
- ✅ Styles CSS
- ✅ Seeder créé
- ✅ Dashboard cliquable

---

## 🚀 Prochaines Étapes

1. **Admin Panel d'Édition:** Permettre l'édition des coordonnées GPS
2. **Géolocalisation:** Obtenir auto les GPS par API
3. **Clustering:** Agrégation des agences proches sur la carte
4. **Filtrage:** Recherche par secteur/région
5. **Notifications:** Alerte incidents par agence

---

## 📞 Support

Pour modifier:
- Horaires: Éditer le JSON dans le seeder
- Chef d'agence: Créer un user avec rôle `gerant`
- Coordonnées: Utiliser Google Maps pour obtenir lat/lng
- Description: Éditer directement en BDD

---

**Le système est opérationnel et prêt pour utilisation!** 🎉
