# Audit de testabilité

## Classification des fichiers

| Fichier/Classe           | Type de test recommandé | Outils utilisables       | Justification                           | Priorité (1-3) |
|--------------------------|-------------------------|--------------------------|-----------------------------------------|----------------|
| `TaskController.php`     | Intégration            | WebTestCase             | Contrôleur principal : gestion des tâches (liste, ajout, édition). Impact direct sur UX           | 1              |
| `Task.php` (Entity)      | Unitaire               | PHPUnit                 | Entité simple, logique métier minimale, facilement testable sans dépendances externes             | 1              |
| `User.php` (Entity)      | Unitaire               | PHPUnit                 | Entité utilisateur, gestion des rôles et mot de passe, base de la sécurité                       | 1              |
| `TaskRepository.php`     | Intégration            | KernelTestCase          | Accès à la base, requêtes Doctrine spécifiques nécessitent BDD fonctionnelle                     | 2              |
| `UserRepository.php`     | Intégration            | KernelTestCase          | Requêtes utilisateurs et gestion sécurité, dépendances Doctrine                                 | 2              |
| `RegistrationFormType.php`| Intégration           | WebTestCase             | Formulaire d'inscription, validation des champs et contraintes, lié à l'interface utilisateur   | 2              |
| `TaskType.php`           | Intégration            | WebTestCase             | Formulaire tâche, validation des champs, interaction avec l'utilisateur                          | 2              |
| `LoginFormAuthenticator.php` | Unitaire + Mock   | PHPUnit + Mock          | Composant sécurité complexe, dépend de sessions, firewalls, et providers, mocks indispensables    | 3              |
| `TaskService.php` (si existant) | Unitaire + Mock | PHPUnit + Mock          | Logique métier complexe, interaction avec d'autres services, dépendances multiples                | 3              |
| `SecurityController.php` (si existant) | Intégration  | WebTestCase             | Contrôleur gérant l'authentification et la gestion de session                                    | 3              |

---

## Stratégie de test adoptée

### Priorité 1 (Tests immédiats)
- **Pourquoi ?**  
  Fichiers simples, peu ou pas de dépendances externes, impact direct sur la logique métier et l’expérience utilisateur.  
- **Fichiers concernés :**  
  `Task.php`, `User.php`, `TaskController.php`

### Priorité 2 (Tests importants)
- **Pourquoi ?**  
  Fichiers avec dépendances à Doctrine et formulaires Symfony, nécessitent un environnement Symfony kernel et une base de données en mémoire (SQLite).  
- **Fichiers concernés :**  
  Repositories, FormTypes (`RegistrationFormType.php`, `TaskType.php`)

### Priorité 3 (Tests complexes)
- **Pourquoi ?**  
  Composants liés à la sécurité ou à la logique métier complexe, nécessitant simulation de contextes (sessions, firewalls) et mocks approfondis.  
- **Fichiers concernés :**  
  `LoginFormAuthenticator.php`, services métiers complexes, contrôleurs de sécurité.

---

## Difficultés identifiées

- **Tests liés à la sécurité (authenticator) :**  
  Nécessité d’utiliser des mocks pour simuler sessions, firewalls, et providers.  
- **Tests sur les repositories :**  
  Nécessité d’une base de données dédiée, souvent SQLite en mémoire pour rapidité et isolation.  
- **Tests des formulaires Symfony :**  
  Requiert le lancement du kernel Symfony avec `WebTestCase` ou `KernelTestCase`.  
- **Temps de test :**  
  Sans bonne configuration (`.env.test`, `phpunit.xml.dist`), les tests peuvent être lents et difficiles à isoler.  
- **Gestion des fixtures :**  
  Besoin de fixtures cohérentes et isolées pour garantir la stabilité des tests d’intégration.

---

## Solutions proposées

- ✅ Utiliser `WebTestCase` pour tester les routes, formulaires et contrôleurs HTTP.  
- ✅ Utiliser `KernelTestCase` pour tester les repositories avec une base SQLite en mémoire pour la rapidité.  
- ✅ Utiliser PHPUnit avec mocks (Prophecy ou PHPUnit native) pour les services complexes comme l’authenticator.  
- ✅ Configurer un environnement de test dédié (`.env.test`) avec une base SQLite et un `phpunit.xml.dist` adapté.  
- ✅ Intégrer DoctrineFixturesBundle pour charger les données de tests et garantir la cohérence.  
- ✅ Documenter la procédure de lancement des tests et la structure dans un `README.md` pour faciliter la maintenance et le passage de relais.

---

## Roadmap des tests

1. **Phase 1 : Tests unitaires sur entités**  
   Couverture des getters/setters, logique métier simple.

2. **Phase 2 : Tests d’intégration sur formulaires et repositories**  
   Validation des requêtes Doctrine, règles de validation des formulaires.

3. **Phase 3 : Tests fonctionnels complexes**  
   Authentification, workflows utilisateur, interactions multiples.

---

Ce plan assure une montée en qualité progressive, en garantissant la stabilité du cœur métier avant d’aborder les cas complexes.

