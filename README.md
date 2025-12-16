# Aplikacja webowa dla pracowników ochrony Miasteczka Studenckiego AGH

Zadaniem aplikacji jest usprawnienie dokumentowania incydentów oraz zdarzeń zaobserwowanych przez ochronę podczas pracy w terenie. System pozwala na porządkowanie zgłoszeń i rezygnację z papierowego obiegu informacji.

## Informacje wstępne
- Repozytorium zawiera skonfigurowaną bazę danych, w której występują dane przykładowe.
- Konta użytkowników: Wszyscy użytkownicy, niezależnie od posiadanej rangi, mają ustawione hasło: `password`.
- Dostęp do bazy danych:
  - Login: `symfony`
  - Hasło: `symfony`

## Opis funkcjonalności
Aplikacja zawiera:
* Panel logowania (email lub login + hasło) z szyfrowaniem haseł algorytmem bcrypt.
* Listę wpisanych zdarzeń.
* Możliwość tworzenia zdarzeń, ich edycji oraz wyświetlania szczegółów z walidacją danych.

## Opis środowiska
* Framework: Symfony LTS
* Baza danych: PostgreSQL
* Wirtualizacja: Docker
* Interfejs użytkownika: Bootstrap

## Instrukcja uruchomienia
1. Klonowanie repozytorium.
2. Konfiguracja środowiska.
3. Zmiana danych do logowania do bazy w razie potrzeby:
```dotenv
DATABASE_URL="pgsql://db_user:db_pass@127.0.0.1:5432/db_name"
```
4. Utworzenie bazy danych.
5. Wykonanie migracji.
6. Uruchomienie z poziomu Docker.