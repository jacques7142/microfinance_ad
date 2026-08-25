(function () {
    'use strict';

    var DICT = {
        // ===== Layout / navigation =====
        "Plateforme coopérative": "Cooperative platform",
        "Général": "General",
        "Tableau de bord": "Dashboard",
        "Sociétaires": "Members",
        "Sociétaire": "Member",
        "Crédits": "Loans",
        "Crédit": "Loan",
        "Messagerie": "Messaging",
        "Paramètres": "Settings",
        "Guichet": "Counter",
        "Dépôts / retraits": "Deposits / withdrawals",
        "Tontine LOGOKU": "LOGOKU tontine",
        "Ma tournée": "My route",
        "Gestion": "Management",
        "Nouveau sociétaire": "New member",
        "Nouveau crédit": "New loan",
        "Reporting": "Reporting",
        "Rapports": "Reports",
        "Administration": "Administration",
        "Utilisateurs": "Users",
        "Agences": "Branches",
        "Agence": "Branch",
        "Rôles & Permissions": "Roles & Permissions",
        "Se déconnecter": "Log out",
        "Toutes agences": "All branches",
        "Mon profil": "My profile",
        "Modifier le profil": "Edit profile",
        "Changer le thème": "Change theme",
        "Mon Compte": "My Account",
        "Opérations": "Operations",
        "Dépôt d'argent": "Money deposit",
        "Retrait d'argent": "Money withdrawal",
        "Demande de prêt": "Loan request",
        "Remboursement": "Repayment",
        "Assistance": "Assistance",
        "Notifications": "Notifications",
        "Tout voir": "View all",
        "Aucune notification pour le moment.": "No notifications at the moment.",
        "Marquer tout comme lu": "Mark all as read",
        "Espace sociétaire": "Member space",

        // ===== Roles =====
        "Administrateur": "Administrator",
        "Caissier": "Cashier",
        "Comptable": "Accountant",
        "Gérant": "Manager",
        "Gérant d'agence": "Branch manager",
        "Gérants d'agence": "Branch managers",
        "Agent de crédit": "Credit agent",
        "Agents de crédit": "Credit agents",
        "Agent de promotion": "Promotion agent",
        "Agents de promotion": "Promotion agents",

        // ===== Common labels =====
        "Nom": "Last name",
        "Prénom": "First name",
        "Nom, prénom ou numéro": "Name, first name or number",
        "Nom *": "Last name *",
        "Prénom *": "First name *",
        "Email": "Email",
        "Téléphone": "Phone",
        "Adresse": "Address",
        "Ville": "City",
        "Rôle": "Role",
        "Rôle(s)": "Role(s)",
        "Rôle principal": "Primary role",
        "Rôles additionnels": "Additional roles",
        "Agence (laisser vide = consolidé multi-agences)": "Branch (leave empty = multi-branch consolidated)",
        "Compte": "Account",
        "Comptes": "Accounts",
        "Comptes d'épargne": "Savings accounts",
        "Montant": "Amount",
        "Statut": "Status",
        "Date": "Date",
        "Heure": "Time",
        "Type": "Type",
        "Solde": "Balance",
        "Période": "Period",
        "Format": "Format",
        "Portée": "Scope",
        "Consolidé": "Consolidated",
        "Consolidé (toutes agences)": "Consolidated (all branches)",
        "Ouvrir": "Open",
        "Voir": "View",
        "Modifier": "Edit",
        "Supprimer": "Delete",
        "Envoyer": "Send",
        "Retour": "Back",
        "Annuler": "Cancel",
        "Enregistrer": "Save",
        "Valider": "Validate",
        "Rejeter": "Reject",
        "Membre": "Member",
        "Lieu": "Location",
        "Payé": "Paid",
        "Payée": "Paid",
        "Reste": "Remaining",
        "Actif": "Active",
        "Inactif": "Inactive",
        "Désactivé": "Disabled",
        "Ouvert le": "Opened on",
        "Échéance": "Installment",
        "Échéance du": "Due date of",
        "Montant dû": "Amount due",
        "Reste dû :": "Remaining:",
        "En retard": "Late",
        "À venir": "Upcoming",
        "Payer": "Pay",
        "Rembourser": "Repay",
        "Signature": "Signature",
        "Signé": "Signed",
        "Signé le": "Signed on",
        "Non signée": "Not signed",
        "Validée": "Validated",
        "Validées": "Validated",
        "Annulée": "Cancelled",
        "Reçue": "Received",
        "Reçues": "Received",
        "Rejetée": "Rejected",
        "Rejetées": "Rejected",
        "Soldée": "Settled",
        "Transmise au gérant": "Forwarded to the manager",
        "Transmises au gérant": "Forwarded to the manager",
        "En instruction": "In review",
        "Collecte tontine": "Tontine collection",
        "Décaissement crédit": "Credit disbursement",
        "Correction": "Correction",
        "Recue": "Received",
        "Validee": "Validated",
        "Rejetee": "Rejected",
        "Soldee": "Settled",
        "Transmise gerant": "Forwarded to manager",
        "Annulee": "Cancelled",
        "Payee": "Paid",
        "A venir": "Upcoming",

        // ===== Admin dashboard =====
        "Administration système": "System administration",
        "Système opérationnel": "System operational",
        "Sociétaires (réseau)": "Members (network)",
        "Agences actives": "Active branches",
        "Collaborateurs": "Collaborators",
        "Tentatives de connexion échouées (24h)": "Failed login attempts (24h)",
        "Vue multi-agences — réseau COOPEC-AD": "Multi-branch view — COOPEC-AD network",
        "Territoire togolais": "Togolese territory",
        "agences": "branches",
        "sociétaires": "members",
        "collaborateurs": "collaborators",
        "profils métiers": "job profiles",
        "Survolez les régions ci-dessus": "Hover over the regions above",
        "sociétaire": "member",
        "Aucune agence dans cette région.": "No branch in this region.",
        "Activité — top 5 agences (30 derniers jours)": "Activity — top 5 branches (last 30 days)",
        "Gestion des utilisateurs": "User management",
        "+ Ajouter": "+ Add",
        "Cassiers": "Cashiers",
        "Comptables": "Accountants",
        "Sociétaires (accès en ligne)": "Members (online access)",
        "Journal d'activité & sécurité": "Activity & security log",
        "Utilisateur": "User",
        "Action": "Action",
        "système": "system",
        "Autorisée": "Authorized",
        "Bloquée": "Blocked",
        "Aucun enregistrement": "No records",

        // ===== Agent credit =====
        "Pipeline de vos dossiers de crédit, par statut.": "Pipeline of your credit files, by status.",
        "Nouvelle demande": "New request",
        "Aucun dossier.": "No files.",

        // ===== Agent promotion =====
        "Agent de promotion — Tontine LOGOKU": "Promotion agent — LOGOKU tontine",
        "Collecté aujourd'hui": "Collected today",
        "Passages effectués": "Rounds completed",
        "Collectes du jour": "Today's collections",
        "Ouvrir ma tournée": "Open my route",
        "Aucune collecte enregistrée aujourd'hui.": "No collection recorded today.",

        // ===== Caissier =====
        "Guichet — Caisse": "Counter — Cash",
        "Dépôts du jour": "Today's deposits",
        "Retraits du jour": "Today's withdrawals",
        "Opérations traitées": "Processed operations",
        "Opérations du jour": "Today's operations",
        "Nouvelle opération": "New operation",
        "Aucune opération aujourd'hui.": "No operation today.",

        // ===== Comptable =====
        "Comptabilité & reporting": "Accounting & reporting",
        "Total actif épargne": "Total savings assets",
        "Encours de crédit validé": "Outstanding validated loans",
        "Plafond TAEG en vigueur": "Current APR ceiling",
        "Répartition des crédits par type": "Loans breakdown by type",
        "Aller aux rapports": "Go to reports",
        "Nombre": "Count",

        // ===== Gérant =====
        "Supervision d'agence": "Branch supervision",
        "Portefeuille crédit validé": "Validated loan portfolio",
        "Sociétaires de l'agence": "Branch members",
        "Crédits ce mois": "Loans this month",
        "Seuil de validation": "Validation threshold",
        "Accès rapide": "Quick access",
        "Générer & consulter": "Generate & view",
        "Ajouter un membre": "Add a member",
        "Saisir une demande": "Enter a request",
        "Mon agence": "My branch",
        "Voir les détails": "View details",
        "Demandes de crédit à valider": "Loan requests to validate",
        "Avis agent": "Agent opinion",
        "Confirmer le rejet ?": "Confirm rejection?",
        "Dossier ne répondant pas aux critères de l'agence.": "File does not meet the branch criteria.",
        "Aucune demande en attente de validation.": "No request awaiting validation.",
        "Derniers rapports": "Latest reports",
        "Produit": "Product",

        // ===== Sociétaires =====
        "Part sociale (F CFA)": "Share capital (F CFA)",
        "Droit d'adhésion (F CFA)": "Membership fee (F CFA)",
        "Enregistrer le sociétaire": "Save member",
        "N° sociétaire": "Member no.",
        "Sociétaire n°": "Member no.",
        "Rechercher": "Search",
        "Aucun sociétaire trouvé.": "No member found.",
        "Soumettez votre demande de crédit. Le dossier est enregistré avec le statut « Reçue » et sera traité par nos équipes.": "Submit your loan request. Your file is recorded with the status «Received» and will be processed by our teams.",
        "Type de crédit": "Loan type",
        "Crédit ordinaire": "Ordinary loan",
        "Crédit de partenariat": "Partnership loan",
        "Crédit tontine adossé": "Backed tontine loan",
        "Sous-type (crédit ordinaire)": "Subtype (ordinary loan)",
        "— Sélectionnez —": "— Select —",
        "— Sélectionner —": "— Select —",
        "— Sélectionnez un compte —": "— Select an account —",
        "— Choisir un sociétaire d'abord —": "— Choose a member first —",
        "Partenaire": "Partner",
        "Montant sollicité (F CFA)": "Requested amount (F CFA)",
        "Durée (mois)": "Duration (months)",
        "Taux d'intérêt annuel (%)": "Annual interest rate (%)",
        "Signature du sociétaire": "Member's signature",
        "Soumettre ma demande": "Submit my request",
        "Effectuez un dépôt sur l'un de vos comptes d'épargne. L'opération est immédiate.": "Make a deposit to one of your savings accounts. The operation is immediate.",
        "Compte à créditer": "Account to credit",
        "— Solde :": "— Balance:",
        "Montant du dépôt (F CFA)": "Deposit amount (F CFA)",
        "Effectuer le dépôt": "Make the deposit",
        "Ex: 50 000": "Ex: 50 000",
        "Ex: 25 000": "Ex: 25 000",
        "Posez vos questions sur la création de compte, une demande de prêt, un dépôt ou tout autre sujet. Un personnel de la COOPEC-AD vous répondra dans les plus brefs délais.": "Ask your questions about account creation, a loan request, a deposit or any other topic. A COOPEC-AD staff member will answer you as soon as possible.",
        "COOPEC-AD — Service client": "COOPEC-AD — Customer service",
        "Équipe": "Team",
        "Aucun message pour le moment.": "No messages for now.",
        "Écrivez à l'équipe de la COOPEC-AD, nous vous répondrons rapidement.": "Write to the COOPEC-AD team, we will answer you quickly.",
        "Écrivez votre message…": "Write your message…",
        "Consultez votre solde, suivez l'avancement de vos opérations et vos notifications en temps réel.": "View your balance, track the progress of your operations and your notifications in real time.",
        "Solde global de l'épargne": "Global savings balance",
        "Épargne totale (DAV + DAT + tontine LOGOKU)": "Total savings (DAV + DAT + LOGOKU tontine)",
        "— Plafond :": "— Limit:",
        "F/j": "F/day",
        "Aucun compte": "No account",
        "Aucun compte d'épargne ouvert.": "No savings account opened.",
        "Plafond crédit adossé :": "Backed credit limit:",
        "— Plafond crédit adossé :": "— Backed credit limit:",
        "Vue d'ensemble": "Overview",
        "Crédits en cours": "Active loans",
        "Crédits sollicités": "Requested loans",
        "Suivi de mes opérations": "Tracking my operations",
        "Opération": "Operation",
        "Crédit #": "Loan #",
        "Aucune opération pour le moment. Effectuez un dépôt, un retrait ou un remboursement pour démarrer.": "No operation for now. Make a deposit, a withdrawal or a repayment to get started.",
        "Avancement de mes demandes de crédit": "Progress of my loan requests",
        "Demandé le": "Requested on",
        "Votre demande a été rejetée. Merci de contacter l'agence pour plus d'informations.": "Your request was rejected. Please contact the branch for more information.",
        "Crédit entièrement remboursé et soldé. Félicitations !": "Loan fully repaid and settled. Congratulations!",
        "Aucune demande de crédit pour le moment.": "No loan request for now.",
        "Mes notifications": "My notifications",
        "Tout marquer comme lu": "Mark all as read",
        "Aucune notification. Vous serez notifié à chaque opération effectuée.": "No notifications. You will be notified for each operation performed.",
        "Bienvenue,": "Welcome,",
        "Accédez à votre espace personnel pour suivre vos comptes, consulter votre épargne et vos crédits, et rester informé des services de la COOPEC-AD.": "Access your personal space to track your accounts, view your savings and loans, and stay informed about COOPEC-AD services.",
        "Faire un prêt": "Take a loan",
        "Soumettre une demande de crédit": "Submit a loan request",
        "Rembourser un prêt": "Repay a loan",
        "Payer vos échéances": "Pay your installments",
        "Créditer votre compte épargne": "Credit your savings account",
        "Débiter votre compte épargne": "Debit your savings account",
        "Solde et crédit": "Balance and credit",
        "Épargne totale": "Total savings",
        "Solde tontine LOGOKU": "LOGOKU tontine balance",
        "Plafond crédit adossé": "Backed credit limit",
        "Dépôt": "Deposit",
        "Retrait": "Withdrawal",
        "Créditez l'un de vos comptes d'épargne. L'opération est immédiate.": "Credit one of your savings accounts. The operation is immediate.",
        "Débitez l'un de vos comptes d'épargne. Vérifiez le plafond journalier.": "Debit one of your savings accounts. Check the daily limit.",
        "Compte à débiter": "Account to debit",
        "Montant du retrait (F CFA)": "Withdrawal amount (F CFA)",
        "Effectuer le retrait": "Make the withdrawal",
        "Remboursement de crédit": "Loan repayment",
        "Sélectionnez une échéance et effectuez votre remboursement.": "Select an installment and make your repayment.",
        "Aucune échéance à rembourser pour le moment.": "No installment to repay for now.",
        "Votre dossier est enregistré avec le statut « Reçue » et sera traité par nos équipes.": "Your file is recorded with the status «Received» and will be processed by our teams.",
        "Aucun compte d'épargne.": "No savings account.",
        "Crédits récents": "Recent loans",
        "Aucune demande de crédit récente.": "No recent loan request.",
        "Montant :": "Amount:",
        "Durée :": "Duration:",
        "mois": "months",
        "Taux :": "Rate:",
        "Montant du remboursement (F CFA)": "Repayment amount (F CFA)",
        "Confirmer le remboursement": "Confirm the repayment",
        "Max :": "Max:",
        "Aucun crédit avec des échéances à rembourser pour le moment.": "No loan with installments to repay for now.",
        "Demander un crédit": "Request a loan",
        "Demande de crédit": "Loan request",
        "Rembourser une échéance": "Repay an installment",
        "Effectuez un retrait depuis l'un de vos comptes d'épargne. Vérifiez le plafond journalier.": "Make a withdrawal from one of your savings accounts. Check the daily limit.",
        "Part sociale": "Share capital",
        "Compte tontine LOGOKU": "LOGOKU tontine account",
        "Solde accumulé :": "Accumulated balance:",
        "Aucun compte d'épargne pour ce sociétaire.": "No savings account for this member.",
        "Aucun crédit.": "No loan.",
        "1. Choisir le sociétaire": "1. Choose the member",
        "2. Opération": "2. Operation",
        "Type d'opération": "Operation type",
        "Montant (F CFA)": "Amount (F CFA)",
        "Valider l'opération": "Validate the operation",
        "Guichet — Dépôt / retrait": "Counter — Deposit / withdrawal",

        // ===== Crédits =====
        "Nouvelle demande de crédit": "New loan request",
        "Type de produit": "Product type",
        "Crédit tontine (adossé LOGOKU)": "Tontine loan (LOGOKU backed)",
        "Enregistrer la demande": "Save the request",
        "Dossier crédit #": "Credit file #",
        "TAEG estimé": "Estimated APR",
        "Plafond légal": "Legal ceiling",
        "Informations": "Information",
        "Sociétaire :": "Member:",
        "Produit :": "Product:",
        "Taux nominal :": "Nominal rate:",
        "Avis de l'agent de crédit :": "Credit agent opinion:",
        "Instruire le dossier": "Review the file",
        "Avis": "Opinion",
        "Transmettre au gérant": "Forward to the manager",
        "Décision": "Decision",
        "Échéancier": "Repayment schedule",

        // ===== Messagerie =====
        "Messagerie sociétaires": "Member messaging",
        "Répondez aux demandes d'assistance des sociétaires (création de compte, demande de prêt, dépôt, retrait, remboursement…).": "Answer members' assistance requests (account creation, loan request, deposit, withdrawal, repayment…).",
        "Aucune conversation pour le moment.": "No conversation for now.",
        "Aucun message": "No message",
        "Conversation —": "Conversation —",
        "Toutes les conversations": "All conversations",
        "Aucun message dans cette conversation.": "No message in this conversation.",
        "Répondre à": "Reply to",

        // ===== Profil =====
        "Modifier mon profil": "Edit my profile",
        "Photo profil": "Profile photo",
        "Photo de profil": "Profile picture",
        "Changer la photo": "Change photo",
        "Max 5 MB (JPEG, PNG, GIF)": "Max 5 MB (JPEG, PNG, GIF)",
        "Email *": "Email *",
        "Biographie": "Biography",
        "Parlez un peu de vous...": "Tell us a bit about yourself...",
        "Maximum 500 caractères": "Maximum 500 characters",
        "Enregistrer les modifications": "Save changes",
        "Informations détaillées": "Detailed information",

        // ===== Rapports =====
        "Modifier le rapport": "Edit the report",
        "Type de rapport": "Report type",
        "Ex: Activité mensuelle": "Ex: Monthly activity",
        "Ex: Juillet 2026": "Ex: July 2026",
        "Générer un rapport": "Generate a report",
        "Toutes agences (consolidé)": "All branches (consolidated)",
        "Générer": "Generate",
        "Supprimer ce rapport ?": "Delete this report?",
        "Aucun rapport généré.": "No report generated.",
        "Date génération": "Generation date",
        "Généré par": "Generated by",

        // ===== Admin agences =====
        "Nouvelle agence": "New branch",
        "Date d'ouverture": "Opening date",
        "Cette agence est le siège (Direction Générale)": "This branch is the head office (General Management)",
        "Créer l'agence": "Create the branch",
        "Réseau d'agences": "Branch network",
        "agence(s) réparties sur l'ensemble du territoire togolais": "branch(es) spread across the whole Togolese territory",
        "Direction Générale": "General Management",

        // ===== Admin roles =====
        "Tout sélectionner": "Select all",
        "Aucune permission configurée.": "No permission configured.",
        "Enregistrer les permissions": "Save permissions",
        "Tout désélectionner": "Deselect all",

        // ===== Admin users =====
        "Nouvel utilisateur": "New user",
        "Un utilisateur peut cumuler plusieurs rôles. Le rôle principal détermine son tableau de bord.": "A user can hold several roles. The primary role determines their dashboard.",
        "Seuil de validation crédit (F CFA)": "Credit validation threshold (F CFA)",
        "Zone de tournée": "Route area",
        "Mot de passe": "Password",
        "Confidentiel : à communiquer à l'utilisateur après la création.": "Confidential: to be communicated to the user after creation.",
        "Confirmation du mot de passe": "Password confirmation",
        "Créer le compte": "Create account",
        "Modifier un utilisateur": "Edit a user",
        "Couleur d'identification": "Identification color",
        "Choisissez une couleur pour identifier cet utilisateur": "Choose a color to identify this user",
        "Utilisateurs internes": "Internal users",
        "Accès rapides": "Quick actions",
        "Attribuer une couleur": "Assign a color",
        "Désactiver": "Disable",
        "Réactiver": "Reactivate",
        "Supprimer définitivement": "Permanently delete",

        // ===== Agences =====
        "Détails Agence": "Branch details",
        "Ouverture:": "Opening:",
        "Localisation": "Location",
        "Latitude:": "Latitude:",
        "Longitude:": "Longitude:",
        "Non définie": "Not defined",
        "À Propos": "About",
        "Aucune description disponible.": "No description available.",
        "Horaires de Fonctionnement": "Opening Hours",
        "Chef d'Agence": "Branch Manager",
        "Aucun gérant assigné": "No manager assigned",
        "Statistiques": "Statistics",
        "Transactions": "Transactions",
        "Contacter l'Agence": "Contact the Branch",
        "Google Maps": "Google Maps",
        "Retour au tableau de bord": "Back to dashboard",
        "Voir les détails complets": "View full details",
        "Fermer": "Close",
        "Appel": "Call",

        // ===== Tontine =====
        "Ma tournée — Tontine LOGOKU": "My route — LOGOKU tontine",
        "Solde accumulé": "Accumulated balance",
        "Mise habituelle": "Usual stake",
        "À visiter": "To visit",
        "Collecter": "Collect",
        "Aucun membre dans votre zone de tournée.": "No member in your route area.",
        "Enregistrer une collecte": "Record a collection",
        "Sélectionnez un membre dans la liste.": "Select a member from the list.",
        "Montant de la mise (F CFA)": "Stake amount (F CFA)",
        "Domicile, marché, lieu d'activité...": "Home, market, place of activity...",
        "Mode de confirmation": "Confirmation mode",
        "Signature sur écran": "On-screen signature",
        "Code OTP par SMS": "OTP code by SMS",
        "Enregistrer la collecte": "Save the collection",
        "Membre sélectionné :": "Selected member:"
    };

    function currentLang() {
        return (document.documentElement.getAttribute('lang') || 'fr').toLowerCase();
    }

    var KEYS = Object.keys(DICT).sort(function (a, b) { return b.length - a.length; });

    function normalize(text) {
        return text.replace(/\s+/g, ' ').trim();
    }

    function canSkip(ch) {
        return /[\s\d.,:;\/()\-—–%'"\[\]&@#]/.test(ch);
    }

    function translateText(text) {
        if (typeof text !== 'string' || !text) return text;
        if (currentLang() !== 'en') return text;
        var trimmed = normalize(text);
        if (!trimmed) return text;

        if (DICT[trimmed] !== undefined) return DICT[trimmed];

        var out = '';
        var i = 0;
        var matchedAny = false;
        while (i < trimmed.length) {
            var ch = trimmed.charAt(i);
            if (canSkip(ch)) {
                out += ch;
                i++;
                continue;
            }
            var hit = null;
            for (var j = 0; j < KEYS.length; j++) {
                var key = KEYS[j];
                if (trimmed.slice(i, i + key.length) === key) {
                    var before = trimmed.charAt(i - 1);
                    var after = trimmed.charAt(i + key.length);
                    if (!canSkip(before) && before !== '') continue;
                    if (!canSkip(after) && after !== '') continue;
                    hit = key;
                    break;
                }
            }
            if (hit === null) return text;
            out += DICT[hit];
            i += hit.length;
            matchedAny = true;
        }
        if (!matchedAny) return text;
        if (out === trimmed) return text;
        return out;
    }

    var ATTRS = ['placeholder', 'title', 'aria-label', 'alt'];

    function apply(root) {
        if (currentLang() !== 'en') return;
        if (!root) root = document.body;
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
            acceptNode: function (node) {
                var p = node.parentElement;
                if (!p) return NodeFilter.FILTER_REJECT;
                var tag = p.tagName;
                if (tag === 'SCRIPT' || tag === 'STYLE' || tag === 'TEXTAREA') return NodeFilter.FILTER_REJECT;
                return NodeFilter.FILTER_ACCEPT;
            }
        });
        var nodes = [];
        while (walker.nextNode()) nodes.push(walker.currentNode);
        for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            var v = n.nodeValue;
            var t = translateText(v);
            if (t !== v) n.nodeValue = t;
        }
        var els = root.querySelectorAll('*');
        for (var j = 0; j < els.length; j++) {
            var el = els[j];
            if (el.tagName === 'SCRIPT' || el.tagName === 'STYLE') continue;
            for (var k = 0; k < ATTRS.length; k++) {
                var attr = ATTRS[k];
                if (el.hasAttribute(attr)) {
                    var ov = el.getAttribute(attr);
                    var nv = translateText(ov);
                    if (nv !== ov) el.setAttribute(attr, nv);
                }
            }
        }
        var docTitle = document.title;
        var nt = translateText(docTitle);
        if (nt !== docTitle) document.title = nt;
    }

    (function () {
        var orig = window.confirm;
        if (!orig || window.__coopecConfirm) return;
        window.__coopecConfirm = true;
        window.confirm = function (msg) {
            return orig.call(this, translateText(String(msg)));
        };
    })();

    window.coopecI18n = {
        apply: apply,
        t: translateText
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { apply(document.body); });
    } else {
        apply(document.body);
    }
})();
