# TP 3 – Formulaires Symfony (E-commerce)

## 📌 Présentation

Ce projet consiste à créer une page produit e-commerce en Symfony intégrant un formulaire permettant d’ajouter un article au panier.  
L’objectif est de mettre en pratique les **formulaires Symfony**, la **validation**, et une **architecture propre** basée sur DTO, les Mappers et les Services.

---

## 📋 Objectif

- Utiliser les formulaires Symfony
- Appliquer la validation des données
- Séparer les responsabilités entre les couches
- Implémenter une logique panier stockée en session
- Respecter les principes SOLID.

---

## ⚙️ Fonctionnalités

- Ajout d’un produit au panier via formulaire
- Validation des champs (quantité, choix)
- Stockage du panier en session
- Messages flash de confirmation

---

## 🧱 Architecture Générale

```text
Formulaire → DTO → Mapper → Value Object → Service → Session
```
---

## 🎯 Ce que j'ai retenu

### 1. Les Composants du Formulaire Symfony

#### **A. Le DTO (Data Transfer Object)**
**Fichier :** `src/DTO/AddToCartDTO.php`

**Rôle :** Objet temporaire qui transporte les données du formulaire au backend.

**Éléments clés :**
- Propriétés privées avec getters/setters
- Annotations de validation :
  - `#[Assert\NotBlank]` : Champ obligatoire
  - `#[Assert\Range(min: 1, max: 10)]` : Valeur entre 1 et 10
  - `#[Assert\Choice]` : Choix parmi une liste

---

#### **B. Le Form Type**
**Fichier :** `src/Form/AddToCartType.php`

**Rôle :** Définit la structure du formulaire.

**Méthode principale :**
```php
public function buildForm(FormBuilderInterface $builder, array $options): void
```

**Méthode `add()` :**
```php
$builder->add('nom_champ', TypeDuChamp::class, ['options'])
```
- **1er paramètre** : Nom du champ (propriété du DTO)
- **2e paramètre** : Type (`IntegerType`, `ChoiceType`, `SubmitType`)
- **3e paramètre** : Options (label, attributs HTML, classes CSS)

**Méthode `configureOptions()` :** Lie le formulaire au DTO
```php
$resolver->setDefaults(['data_class' => AddToCartDTO::class]);
```

---

#### **C. Le Controller**
**Fichier :** `src/Controller/ProductController.php`

**Étapes de traitement du formulaire :**

1. **Créer le formulaire :**
```php
$form = $this->createForm(AddToCartType::class, $addToCartDTO);
```

2. **Récupérer les données :**
```php
$form->handleRequest($request);
```

3. **Valider et traiter :**
```php
if ($form->isSubmitted() && $form->isValid()) {
    // Traitement des données
}
```

4. **Afficher un message :**
```php
$this->addFlash('success', 'Message');
```

---

#### **D. La Vue Twig**
**Fichier :** `templates/product/show.html.twig`

**Fonctions Twig pour formulaires :**
- `form_start(form)` : Balise `<form>` ouvrante + token CSRF
- `form_label(form.champ)` : Label du champ
- `form_widget(form.champ)` : Input/Select du champ
- `form_errors(form.champ)` : Messages d'erreur
- `form_end(form)` : Balise `</form>` fermante

**Afficher les messages flash :**
```twig
{% for message in app.flashes('success') %}
    <div class="alert alert-success">{{ message }}</div>
{% endfor %}
```

---

### 2. les Bonnes Pratiques :

**Principe :** Ne jamais stocker le DTO directement en session.

**Flow :**
```
Formulaire → DTO → Mapper → CartItem → Session
```

#### **A. Le Value Object (CartItem)**
**Fichier :** `src/ValueObject/CartItem.php`

- Objet **immutable** (ne peut pas être modifié)
- Méthodes : `toArray()`, `fromArray()`, `addQuantity()`
- Représente un article du panier

#### **B. Le Mapper**
**Fichier :** `src/Mapper/CartMapper.php`

**Rôle :** Transformer le DTO en CartItem
```php
public function dtoToCartItem(AddToCartDTO $dto): CartItem
{
    return new CartItem($dto->getColor(), $dto->getQuantity());
}
```

#### **C. Interface CartServiceInterface**
**Fichier :** `src/Service/Interface/CartServiceInterface.php`

Définit le contrat du service panier :
- `addItem(AddToCartDTO $dto): void`
- `getItems(): array`
- `getItemCount(): int`
- `clear(): void`

#### **D. Implémentation SessionCartService**
**Fichier :** `src/Service/Implementation/SessionCartService.php`

Stocke le panier en session PHP.

**Injection du Mapper :**
```php
public function __construct(
    private readonly RequestStack $requestStack,
    private readonly CartMapper $cartMapper
) {}
```

---

### 4. Configuration

**Fichier :** `config/services.yaml`

Lie l'interface à l'implémentation :
```yaml
App\Service\Interface\CartServiceInterface: '@App\Service\Implementation\SessionCartService'
```

---
