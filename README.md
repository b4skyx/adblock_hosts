## Adguard Filter lists converted in Hosts file format.

The folder hierarchy is as follows

```
filter-lists.txt
src
    ├── converter.php
    └── sources.json
```

The ``sources.json`` contains the upstream for the Adguard filter list.

``converter.php`` has the source code

---

## Dependencies

You will need to have php installed in your machine.

**Linux**:

For debian based distros

``sudo apt-get install php``

Arch / Arch derrivatives

``sudo pacman -S php``


**Windows:**

You could obtain the executable installer from the php official website.
For more information, visit [](https://www.php.net/manual/en/install.windows.php).

---

## Running


To obtain the latest sources, clone the repo
```
git clone https://github.com/b4skyx/adblock_hosts.git
cd adblock_hosts
php src/converter.php
```

You will have the hosts in project home folder which you can use wherever applicable.
