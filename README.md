# Czym jest projekt csv-to-db? 
Projekt csv-to-db powstał w celu uzupełnienia generowanych przez Presta Shop dokumentów XML, o informacje niedostępne w bazie danych MySQL, oraz ograniczał ilość produktów do konkretnych kodów SKU.

##  Projekt dzieli się na 2 części:
1. importer danych z pliku CSV do bazy danych
2. modyfikację skryptu do generowania dokumentów XML

## Instalacja importera CSV
1. przenieś katalog csv-to-db z całą zawartością na twój hosting www
2. utwórz w bazie MySQL tabelę w której będziesz przetrzymywał dodatkowe pola (nazwa dowolna)
3. tabela powinna zawierać następujące pola:
```
'id' INT(10) NOT NULL AUTO_INCREMENT
'sku' VARCHAR(100) NOT NULL
'vat' VARCHAR(10) NOT NULL
'lenght' VARCHAR(10) NOT NULL 
'width' VARCHAR(10) NOT NULL 
'height' VARCHAR(10) NOT NULL
'net_weight' VARCHAR(10) NOT NULL
'gross_weight' VARCHAR(10) NOT NULL
PRIMARY KEY('id')
```
 4. skonfiguruj połączenie w pliku 'index.php' (linia 32-36)
```
$db_host = "";  // DB host
$db_user = "";  // DB user
$db_pass = "";  // DB password
$db_name = "";  // DB name
$db_table = ""; // BD table
```
## Działanie importera CSV
1. otwórz stronę http://twoja.domena/sciezka-do/csv-to-db/
2. pobierz wzór pliku CSV dostępny w prawym rogu stopki 
3. uzupełnij plik danymi
4. kliknij przycisk "Wybierz plik" w nagłówku strony
5. wskaż uzupełniony przez ciebie plik CSV na dysku lokalnym
6. kliknij przycisk "Import" 
7. po prawidłowym imporcie otrzymasz komunikat "Plik zaimportowany do bazy danych"
8. jeżeli w bazie pojawią się jakieś wpisy, na stronie pojawi się tabela ze spisem całej bazy

**UWAGA: tabela z dodatkowymi danymi zeruje się przy każdorazowym imporcie pliku CSV!**

## Modyfikacja skryptu generującego XML
1. dołącz do swojego zapytania utworzoną tabelę za pomocą `JOIN nazwa_tabeli kg ON kg.sku = p.reference`
2. do polecenia do zapytania dołącz następujące kolumny: `SELECT kg.vat, kg.lenght, kg.width, kg.height, kg.net_weight, kg.gross_weight` **Pamiętaj aby użyć prefiksu np `kg.` nazw kolumn aby uniknąć kolizji**
3. w funkcji prepareXML dopisz następujące linie:
```
if (isset($row[$i]['vat'])){$xml .= '<Vat>'.$row[$i]['vat']."</Vat>\r\n"; }
if (isset($row[$i]['lenght'])){$xml .= '<Szt_dlugosc>'.$row[$i]['lenght']."</Szt_dlugosc>\r\n";}
if (isset($row[$i]['width'])){$xml .= '<Szt_szerokosc>'.$row[$i]['width']."</Szt_szerokosc>\r\n";}
if (isset($row[$i]['height'])){$xml .= '<Szt_wysokosc>'.$row[$i]['height']."</Szt_wysokosc>\r\n";}
if (isset($row[$i]['net_weight'])){$xml .= '<Szt_waga_netto>'.$row[$i]['net_weight']."</Szt_waga_netto>\r\n";}
if (isset($row[$i]['gross_weight'])){$xml .= '<Szt_waga_brutto>'.$row[$i]['gross_weight']."</Szt_waga_brutto>\r\n";}
```
4. aby zdjęcia były wyświetlane jako osobna linia zamień linię
`if (!empty($image)) {$xml .= '<Link_do_zdjecia>'.$image."</Link_do_zdjecia>\r\n";}`
na następującą:
```
if (isset($row[$i]['images'])) {
 $images = explode("; ", $row[$i]['images']);
 $xml .= "<Linki_do_zdjec>\r\n";
 foreach ($images as $image){
  // Link_do_zdjecia
  if (!empty($image)) {$xml .= '<Link_do_zdjecia>'.$image."</Link_do_zdjecia>\r\n"}
 }
 $xml .= "</Linki_do_zdjec>\r\n";
}
```
