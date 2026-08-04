<!-- Modal Détails Agence -->
<div id="agenceModal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Détails Agence</h2>
            <button class="modal-close" onclick="closeAgenceModal()"><x-icon name="x" size="20"/></button>
        </div>
        
        <div class="modal-body">
            <!-- Carte Mini -->
            <div id="modalMap" style="width: 100%; height: 250px; border-radius: 8px; margin-bottom: 16px; border: 1px solid var(--line);"></div>
            
            <!-- Informations Agence -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; margin-bottom: 4px;">Sociétaires</div>
                    <div style="font-size: 20px; font-weight: 700; color: var(--navy); font-family: 'Sora';" id="modalSocietaires">0</div>
                </div>
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; margin-bottom: 4px;">Statut</div>
                    <span id="modalStatut" class="badge b-green"><x-icon name="check" size="12"/> Actif</span>
                </div>
            </div>
            
            <!-- Adresse -->
            <div style="margin-bottom: 16px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;"><x-icon name="map-pin" size="14"/> Adresse</div>
                <div style="font-size: 13px; color: var(--ink);" id="modalAdresse">—</div>
            </div>
            
            <!-- Chef d'Agence -->
            <div style="margin-bottom: 16px;">
                <div style="font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; margin-bottom: 6px;"><x-icon name="briefcase" size="14"/> Chef d'Agence</div>
                <div id="modalGerant" style="padding: 12px; background: var(--bg); border-radius: 8px;">
                    <div style="font-size: 12px; font-weight: 700;">—</div>
                </div>
            </div>
            
            <!-- Contacts -->
            <div style="display: flex; gap: 8px;">
                <a id="modalEmailBtn" href="#" class="btn btn-navy btn-sm" style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 6px;"><x-icon name="email" size="14"/> Email</a>
                <a id="modalPhoneBtn" href="#" class="btn btn-ghost btn-sm" style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 6px;"><x-icon name="phone" size="14"/> Appel</a>
            </div>
        </div>
        
        <div class="modal-footer">
            <a id="modalDetailBtn" href="#" class="btn btn-navy" style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center; gap: 6px;">Voir les détails complets <x-icon name="arrow-right" size="14"/></a>
            <button onclick="closeAgenceModal()" class="btn btn-ghost">Fermer</button>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
    }
    
    .modal-content {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        border-bottom: 1px solid var(--line);
    }
    
    .modal-header h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: var(--muted);
        padding: 0;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-close:hover {
        color: var(--ink);
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        display: flex;
        gap: 8px;
        padding: 16px 20px;
        border-top: 1px solid var(--line);
    }
</style>

<script>
let currentAgenceId = null;
let modalMap = null;

function openAgenceModal(agenceId) {
    currentAgenceId = agenceId;
    
    // Récupérer les données de l'agence
    fetch(`/api/agence/${agenceId}`)
        .then(response => response.json())
        .then(data => {
            const agence = data.agence;
            const gerant = data.gerant;
            
            // Remplir le modal
            document.getElementById('modalTitle').textContent = agence.nom;
            document.getElementById('modalSocietaires').textContent = agence.societaires_count || 0;
            document.getElementById('modalAdresse').textContent = agence.adresse;
            document.getElementById('modalStatut').innerHTML = agence.actif ? 'Actif' : 'Inactif';
            document.getElementById('modalStatut').className = agence.actif ? 'badge b-green' : 'badge b-red';
            
            // Chef d'agence
            if (gerant) {
                document.getElementById('modalGerant').innerHTML = `
                    <div style="font-weight: 700;">${gerant.prenom} ${gerant.nom}</div>
                    <div style="font-size: 11px; color: var(--muted);">${gerant.email}</div>
                `;
                document.getElementById('modalEmailBtn').href = `mailto:${gerant.email}`;
                if (gerant.telephone) {
                    document.getElementById('modalPhoneBtn').href = `tel:${gerant.telephone}`;
                }
            }
            
            // Bouton détails
            document.getElementById('modalDetailBtn').href = `/agences/${agenceId}`;
            
            // Afficher le modal
            document.getElementById('agenceModal').style.display = 'flex';
            
            // Initialiser la carte
            setTimeout(() => {
                initializeModalMap(data.coordonnees.lat, data.coordonnees.lng, agence.nom);
            }, 100);
        });
}

function closeAgenceModal() {
    document.getElementById('agenceModal').style.display = 'none';
    if (modalMap) {
        modalMap.remove();
        modalMap = null;
    }
}

function initializeModalMap(lat, lng, name) {
    if (modalMap) {
        modalMap.remove();
    }
    
    modalMap = L.map('modalMap').setView([lat, lng], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19,
    }).addTo(modalMap);
    
    const marker = L.marker([lat, lng]).addTo(modalMap);
    marker.bindPopup(`<strong>${name}</strong>`).openPopup();
}

// Fermer le modal en cliquant en dehors
document.addEventListener('click', function(event) {
    const modal = document.getElementById('agenceModal');
    if (event.target === modal) {
        closeAgenceModal();
    }
});

// Clé ESC pour fermer
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAgenceModal();
    }
});
</script>
