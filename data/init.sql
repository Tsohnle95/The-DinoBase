CREATE TABLE group_1_catalogue_admin (
    account_id INT(3) AUTO_INCREMENT PRIMARY KEY,
    users VARCHAR(16) NOT NULL,
    hashed_pass VARCHAR(72) NOT NULL,
    profile_url VARCHAR(200) NULL
);

INSERT INTO group_1_catalogue_admin (users, hashed_pass)
VALUES ('instructor', '$2a$12$4bJ0MLbN81BXdUZqb85TPuuULkK121tScDArkf9tUsizfXcmvIfPC'), 
('long', '$2a$12$9q6.F24utcDKIe8fdOV1bejY19Db6D/oZFkW41Br1lu4qPm5hudaW'), 
('ethan', '$2a$12$nCba3YVBDg6N/xhW.VR9T.WORFkxLYVibb4t536zOSSjGlgWEPpSW'), 
('ty', '$2a$12$hdZQDLTgb459EvVlNHsND..k0V3yIETb80alyHeZwD.gB34Uqr23G'), 
('rodney', '$2a$12$cBgcb3AuA0C.fP6LR.CAgekm4X5qHVIQYlNE1yYXn3jb7SJ16w3QG'); 


CREATE TABLE `dinobase` (
    `id` INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `genus_name` VARCHAR(100) NULL,
    `species_name` VARCHAR(100) NOT NULL,
    `status` ENUM('Valid', 'Nomen Nudum', 'Nomen Dubium', 'Rejected') NOT NULL,
    `year` INT NOT NULL,
    `size` DECIMAL(7,2) UNSIGNED NULL,
    `weight` DECIMAL(15,1) UNSIGNED NULL,
    `continent` INT NOT NULL CHECK (continent > 0 AND continent < 8),
    `country` VARCHAR(50) NOT NULL,
    `time_period` ENUM('Early Triassic', 'Late Triassic', 'Early Jurassic', 'Late Jurassic', 'Early Cretaceous', 'Late Cretaceous') NULL,
    `description` TEXT NOT NULL,
    `type` ENUM('Terrestrial','Avian','Aquatic') NULL,
    `is_alive` BOOLEAN NOT NULL,
    `url` VARCHAR(200) NOT NULL,
    `user_id` INT NULL,
    FOREIGN KEY (user_id) REFERENCES group_1_catalogue_admin(account_id)
);

/* the url in the database will contain the image id and image name. The image id will be the primary key.
That way, the database knows what image is connected to where. If there is no image uploaded, it will default to 'placeholder.avif'. */

INSERT INTO `dinobase`(`id`, `genus_name`, `species_name`, `status`,`year`,`size`,`weight`,`continent`,`country`,`time_period`,`description`,`type`,`is_alive`,`url`)
VALUES 
(1, 'Stegosaurus','stenops','Valid',1887,7.5,1560,1,'USA','Late Jurassic','Stegosaurus was a large, plant-eating dinosaur from the Late Jurassic period, easily recognized by the two rows of tall, bony plates along its back and the four sharp spikes on its tail. It walked on four sturdy legs, had a small head for its body size, and likely used its tail spikes for defense against predators.','Terrestrial',0,'1.avif'),
(2, 'Kentrosaurus','aethiopicus','Valid',1909,4.5,950,3,'Tanzania','Late Jurassic','Kentrosaurus aethiopicus was a small, spiky stegosaur from the Late Jurassic of Africa. It had rows of pointed plates along its back and long, sharp spikes on its hips and tail, which it used for defense. More lightly built than Stegosaurus, it moved on four legs and fed on low plants.','Terrestrial',0,'2.avif'),
(3, 'Triceratops','Horridus','Valid',1887,9,7500,1,'USA','Late Cretaceous','Triceratops horridus was a large, herbivorous dinosaur from the Late Cretaceous, known for its three facial horns and a broad bony frill. It walked on four sturdy legs, had a powerful beak for cropping plants, and likely used its horns for defense and display.','Terrestrial',0,'3.avif'),
(4, 'Pachyhrinosaurus','pertorum','Valid',2008,7.5,5400,1,'USA','Late Cretaceous','Pachyrhinosaurus perotorum was a horned dinosaur from Late Cretaceous Alaska, notable for its thick, bony nose boss instead of large horns. It lived in cold, seasonal environments, moved in herds, and used its heavy skull structures for defense and possibly display.','Terrestrial',0,'4.avif'),
(5, 'Sinoceratops','zhuchengensis','Valid',2010,7,6200,5,'China','Late Cretaceous', 'Sinoceratops was a medium-sized ceratopsian from Late Cretaceous China, known for its large frill lined with unique forward-curving hornlets and a single small nose horn. It lived in herds and fed on tough, low-growing plants.','Terrestrial',0,'5.avif'),
(6, 'Parasaurolophus','tubican','Valid',1993,9.7,5300,1,'USA','Late Cretaceous','Parasaurolophus tubicen was a large hadrosaur from the Late Cretaceous, known for its long, backward-curving hollow crest. This crest likely helped it make loud, resonant calls and may have been used for display or communication within its herd. It walked on both two and four legs and fed on a variety of plants.','Terrestrial',0,'6.avif'),
(7, 'Edmontosaurus', 'annectans', 'Valid',1892,15,14000,1,'Canada','Late Cretaceous', 'Edmontosaurus annectens was a large, duck-billed hadrosaur from the Late Cretaceous of North America. It had a long, flat snout, a flexible beak for cropping plants, and could walk on two or four legs. Known from well-preserved fossils, it was a gentle herbivore that lived and traveled in herds.','Terrestrial',0,'7.avif'),
(8, 'Ouranosaurus','nigeriensus', 'Valid',1976,8.3,2200,3,'Nigeria','Early Cretaceous', 'Ouranosaurus was a medium-sized herbivorous dinosaur from the Early Cretaceous of Africa, recognized for the tall neural spines on its back that formed a sail-like structure. It had a long, low skull with a duckbill-like snout and walked on both two and four legs while grazing on vegetation.', 'Terrestrial',0,'8.avif'),
(9, 'Brachiosaurus', 'altithorax', 'Valid',1903,26.5,50000,1,'USA','Late Jurassic', 'Brachiosaurus was a gigantic, long-necked sauropod from the Late Jurassic, known for its front legs being longer than its hind legs, giving it a giraffe-like stance. It used its towering neck to reach high vegetation and was one of the largest land animals to ever live.', 'Terrestrial',0,'9.avif'),
(10,'Diplodocus', 'hallorum','Valid',2015,30,21000,1,'USA','Late Jurassic','Diplodocus was a long, slender sauropod from the Late Jurassic, known for its extremely long neck and whip-like tail. It walked on four pillar-like legs and fed on mid- to high-level vegetation, using its lightweight skull and peg-like teeth to strip leaves from branches.', 'Terrestrial',0,'10.avif'),
(11, 'Alamosaurus','sanjuanesis', 'Valid',1922,26,38000,1,'USA','Late Cretaceous', 'Alamosaurus was a huge, long-necked sauropod from the Late Cretaceous of North America. It had a massive body, column-like legs, and a long tail, thriving in warm, open environments. As one of the last surviving sauropods, it fed on high vegetation and may have grown to truly enormous sizes.', 'Terrestrial',0,'11.avif'),
(12, 'Nigersaurus', 'taqueti', 'Valid', 1999, 10,1900,3,'Niger','Early Cretaceous','Nigersaurus was a small, long-necked sauropod from the Early Cretaceous of Africa, known for its wide, vacuum-like snout filled with over 500 replaceable teeth. It grazed close to the ground, sweeping plants with its broad, flattened muzzle, and had a lightweight skull adapted for constant feeding.','Terrestrial',0,'12.avif'),
(13, 'Velociraptor', 'mongoliensis','Valid',1924,2.65,38,5,'Mongolia', 'Late Cretaceous', 'Velociraptor was a small, feathered dromaeosaur from the Late Cretaceous of Mongolia. About the size of a turkey, it had a long, stiffened tail for balance, sharp curved claws—including a sickle-shaped claw on each foot—and a narrow, lightly built skull with serrated teeth. It was fast, agile, and likely hunted small prey, using quick strikes rather than the large-pack behavior often shown in movies.', 'Terrestrial',0,'13.avif'),
(14, 'Carnotaurus', 'sastrei','Valid',1985,7.8,1850,2,'Argentina','Late Cretaceous','Carnotaurus was a medium-sized, fast-running theropod dinosaur from Late Cretaceous South America. It had a deep, slender skull with two short bull-like horns above its eyes, tiny but strong forelimbs, long powerful legs, and a muscular tail for quick bursts of speed. Its body was likely covered in bumpy scales, giving it a distinctive, rugged appearance.','Terrestrial',0,'14.avif'),
(15, 'Spinosaurus', 'aegyptiacus','Valid', 1915,14.4,6500,3,'Egypt','Early Cretaceous','Spinosaurus aegyptiacus was one of the largest known predatory dinosaurs, living in North Africa during the early-Cretaceous. It had a long, crocodile-like snout filled with conical teeth for catching fish, a powerful but low-slung body, and long forelimbs with large claws. Its most distinctive feature was the tall sail—or possibly a hump—formed by elongated neural spines along its back. Adapted for a semi-aquatic lifestyle, Spinosaurus likely hunted in rivers and coastal environments, using its paddle-like tail and robust limbs to maneuver in the water.', 'Aquatic',0,'15.avif'),
(16, 'Tyrannosaurus','rex','Valid',1905,13,9500,1,'Canada','Early Jurassic','Tyrannosaurus rex was one of the largest land predators of all time, living in western North America during the early-jurassic. It had a massive skull with powerful, bone-crushing jaws lined with thick, serrated teeth. Its body was muscular and balanced by a long, heavy tail, while its legs were strong and built for bursts of speed. Despite its huge size, T. rex had famously small but strong forelimbs with two claws. Its keen senses—especially vision and smell—made it an apex predator at the top of its ecosystem.','Terrestrial',0,'16.avif'),
(17, 'Therizinosaurus','cheloniformis','Valid',1954,9,4500,5,'Mongolia','Late Cretaceous','Therizinosaurus was a large, unusual theropod dinosaur from Late Cretaceous Asia, best known for its extremely long, scythe-like claws—some of the longest of any land animal, reaching up to a meter. It had a small head with a beaked mouth, a long neck, and a bulky, feathered body supported by strong hind limbs. Unlike most theropods, it was likely herbivorous or omnivorous, using its claws to pull down vegetation. Its combination of giant claws, feathers, and pot-bellied build makes it one of the most distinctive dinosaurs ever discovered.','Terrestrial',0,'17.avif'),
(18,'Concavenator', 'corcovatus','Valid',2010,5.8,550,4,'Spain','Early Cretaceous', 'Concavenator was a medium-sized carnivorous dinosaur notable for the distinctive tall, triangular crest or hump on its back, formed by elongated vertebrae just in front of the hips. It had a sleek, agile body, long legs for running, and a skull with sharp, serrated teeth suited for hunting smaller animals. Fossils show evidence of quill-like structures on its forearms, suggesting it may have had primitive feathers.','Terrestrial',0,'18.avif'),
(19, 'Quetzalcoatlus','northropi','Valid',1975,10,250,1,'USA','Late Cretaceous','Quetzalcoatlus was one of the largest flying animals to ever exist, a giant pterosaur from Late Cretaceous North America. It had an enormous wingspan—likely around 10 to 11 meters—with long, narrow wings suited for efficient soaring. Its body was lightweight and hollow-boned, and it stood as tall as a giraffe when on the ground, supported by long, sturdy limbs. Quetzalcoatlus had a long, toothless beak, possibly used for stalking prey on land or foraging in open habitats.','Avian',0,'19.avif'),
(20,'Thanatosdrakon','amaru','Valid',2022,8.6,200,2,'Argentina','Late Cretaceous', 'Thanatosdrakon amaru was a giant azhdarchid pterosaur from the Late Cretaceous of Argentina, known from exceptionally well-preserved remains. It had an enormous wingspan—estimated around 7 to 9 meters—with long, hollow bones and a lightweight frame typical of large pterosaurs.','Avian',0,'20.avif'),
(21, 'Pteranodon', 'longiceps','Valid',1879,6.4,55,1,'USA','Late Cretaceous', 'Pteranodon longiceps was a large Late Cretaceous pterosaur from North America, recognizable by its long, backward-pointing cranial crest and toothless beak. With a wingspan of around 6 meters, it was a skilled soarer over ancient seas, likely feeding on fish and other small marine animals. Its lightweight, hollow-boned body and long wings made it one of the most iconic flying reptiles of its time.', 'Avian',0,'21.avif'),
(22, 'Barbaridactylus', 'grandis','Valid',2018,6.6,23,3,'Morocco','Late Jurassic','Barbaridactylus grandis was a large nyctosaurid pterosaur from the Late Cretaceous of Morocco. It had long, slender wings specialized for efficient soaring over coastal environments, along with a lightweight, hollow-boned skeleton typical of pterosaurs. Like other nyctosaurids, it likely had reduced or even absent forelimb claws and an elongated wing finger supporting a broad wing membrane.','Avian',0,'22.avif'),
(23, 'Dimorphadon','macronyx','Valid',1859,1.3,2,4,'UK','Late Cretaceous','Dimorphodon macronyx was an early Jurassic pterosaur from England, known for its relatively large head, short tail, and two distinct types of teeth—hence its name “two-form tooth.” It had a wingspan of about 1.4 meters and likely hunted insects and small vertebrates. Its strong, clawed limbs suggest it could climb and perch, making it a versatile flyer and predator of its time.','Avian',0,'23.avif'),
(24, 'Dsungaripterus','weii','Valid',1964,3.5,4,5,'China','Early Cretaceous','Dsungaripterus weii was a Late Jurassic to Early Cretaceous pterosaur from China, known for its long, narrow, toothless beak with a specialized tip and robust jaw teeth at the back for crushing hard-shelled prey. It had a wingspan of about 3 meters and likely fed on mollusks and crustaceans, using its strong jaws to break shells. Its sturdy limbs suggest it was capable of walking on land as well as flying.','Avian',0,'24.avif'),
(25,'Jeholopterus','ningchengensis','Valid',2002,0.9,0.1,5,'China','Late Jurassic','Jeholopterus ningchengensis was a small, Late Jurassic to Early Cretaceous pterosaur from China, notable for its broad wings and evidence of dense body covering, likely pycnofibers (hair-like filaments), suggesting it was warm-blooded. It had a short skull with sharp teeth, hinting at a possible insectivorous or even hematophagous (blood-feeding) diet. With a wingspan of about 0.9 meters, it was adapted for agile flight through forests, possibly gliding between trees while hunting small prey.','Avian',0,'25.avif'),
(26, 'Shonisaurus', 'popularis','Valid',1920,15,30000,1,'USA','Late Triassic','Shonisaurus popularis was a giant ichthyosaur from the Late Triassic of North America. It could reach lengths of over 15 meters, had a long, streamlined body and a pointed snout for catching fish and squid, and was fully adapted to life in the ocean with paddle-like limbs and a powerful tail for fast swimming.','Aquatic',0,'26.avif'),
(27, 'Nautilus','pompilius','Valid',1979,0.3,2,5,'Philippines','Early Triassic','A nautilus is a marine cephalopod known for its beautiful spiral shell divided into gas-filled chambers that help it control buoyancy. It has dozens of small, sticky tentacles, a simple eye, and swims using jet propulsion. Often called a “living fossil,” the nautilus has changed little in hundreds of millions of years.','Aquatic',1,'placeholder.avif'),
(28, 'Plesiosaurus', 'dolichodeirus','Valid',1821,3.5,175,4,'England','Early Jurassic','Plesiosaurus dolichodeirus was a small, early Jurassic marine reptile known for its long, flexible neck, streamlined body, and four strong, paddle-like flippers used for agile underwater “flight.” Reaching about 3.5 meters in length, it hunted fish and cephalopods in shallow coastal seas.','Aquatic',0,'28.avif'),
(29, 'Latimeria','chalumnae','Valid',1938,2,75,3,'South Africa','Early Triassic','Latimeria chalumnae is a rare, deep-sea lobe-finned fish with a distinctive bluish coloration and limb-like fins. It lives in underwater caves and steep volcanic slopes at depths of 150-700 meters. Often called a “living fossil,” it has a unique hinged skull, slow metabolism, and unusual reproduction (live birth), making it one of the most biologically fascinating animals still alive today.','Aquatic',1,'placeholder.avif'),
(30, 'Mosasaurus','hoffmannii','Valid',1829,17,15000,4,'Netherlands','Late Cretaceous','Mosasaurus hoffmannii was a giant marine reptile with a long, powerful body, strong jaws lined with conical teeth, and paddle-like limbs, making it a top predator in Late Cretaceous seas. Fossils show it could ambush large prey such as fish, ammonites, and even other marine reptiles.','Aquatic',0,'30.avif'),
(31, 'Otodus', 'megalodon', 'Valid',1843,18,70000,6,'Austrilia','Late Cretaceous','Otodus megalodon was a massive predatory shark, famous for its enormous teeth and powerful bite. It likely preyed on whales and large marine mammals and was one of the largest and most formidable predators in Earths history.','Aquatic',0,'31.avif'),
(32, 'Dunkleosteus','terrelli','Valid',1873,10,4000,1,'USA','Late Cretaceous','Dunkleosteus terrelli was a giant armored fish with bony plates forming a powerful jaw instead of teeth, capable of crushing prey with tremendous force. It was one of the top predators of the Late Devonian seas, feeding on fish and other marine organisms.','Aquatic',0,'32.avif'),
(33, 'Archelon', 'ischyros','Valid',1895,4.5,2000,1,'USA','Late Cretaceous','Archelon ischyros was a giant marine turtle with a leathery shell and large flippers, adapted for open-ocean swimming. It likely fed on jellyfish and soft-bodied marine animals, and is one of the largest turtles known from the fossil record.','Aquatic',0,'33.avif'),
(34, 'Kronosaurus','queenslandicus','Valid',1924,10,8000,6,'Australia','Early Cretaceous','Kronosaurus queenslandicus was a massive marine predator with a large, elongated skull and powerful jaws filled with sharp teeth. It likely hunted fish, other marine reptiles, and large prey, making it one of the top predators in Early Cretaceous seas.','Aquatic',0,'34.avif'),
(35, 'Tylosaurus', 'proriger','Valid',1872,14,13000,1,'USA','Late Cretaceous','Tylosaurus proriger was a large marine reptile in the mosasaur family, with a long, streamlined body and powerful tail for swimming. It was an apex predator, feeding on fish, ammonites, and other marine reptiles, and is notable for its elongated snout which may have been used to ram prey.','Aquatic',0,'35.avif'),
(36, 'Wuerhosaurus', 'homheni','Valid',1973,7,4000,5,'China','Early Cretaceous','It is among the last known stegosaurs. Its back had bony plates (though plate shape is uncertain due to fragmentary fossils), and likely a tail with spikes (a “thagomizer”) for defense. Its body was relatively broad and low to the ground, possibly adapted to feeding on low vegetation.','Terrestrial',0,'36.avif'),
(37,'Deinocheirus', 'mirificus','Valid',1970,11,6000,5,'Mongolia','Late Cretaceous','A large and bizarre theropod. It had enormous, 2.4 metre long forelimbs with big blunt claws. It had a long toothless duck like beak and a bulky humped back body supported by elongated neural spines. It was likely omnivorous — eating plants, possibly fish and small animals', 'Terrestrial', 0,'37.avif'),
(38, 'Ankylosaurus', 'magniventri','Valid',1908,10,5600,1,'USA','Late Cretaceous','Ankylosaurus was a heavily-armoured, quadrupedal herbivore with a broad, low body. Its body was protected by bony plates (osteoderms), had a wide, flat skull with a beak and small leaf-shaped teeth for cropping vegetation. Its most distinctive defensive weapon was a large bony club at the end of its tail, likely used to defend against predators.','Terrestrial',0,'38.avif'),
(39, 'Australovenator','wintonensis','Valid',2009,6,500,6,'Australia','Late Cretaceous', 'A medium-sized, relatively lightweight carnivorous theropod. It had long, slender hindlimbs, making it likely quite agile. Its forelimbs were relatively long and ended in large, sharp claws — likely used to catch and hold prey.','Terrestrial',0,'39.avif'),
(40, 'Crichtonsaurus','bohlini','Valid',2002,3.5,475,5,'China','Late Cretaceous','Crichtonsaurus was a small, terrestrial, armored herbivorous dinosaur it was related to ankylosaurus.','Terrestrial',0,'40.avif'),
(41, 'Suchomimus', 'tenerensis','Valid',1998,11,3800,3,'Niger','Early Cretaceous', 'Suchomimus was a large theropod with a long, narrow, crocodile like snout filled with conical teeth. They were ideal for catching fish. It had powerful forelimbs with large curved claws (especially a massive thumb claw), likely used to catch or hold prey.','Aquatic',0,'41.avif'),
(42, 'Glacialisaurus', 'hammeri','Nomen Nudum',2007,6.2,3000,7,'Antarctica', 'Early Jurassic','Glacialisaurus hammeri was a large, early Jurassic basal sauropodomorph dinosaur from Antarctica. It was primarily herbivorous, had robust hind limbs, and likely walked mostly on two legs.','Terrestrial',0,'42.avif'),
(43, 'Cryolophosaurus','ellioti','Valid',1994,7.5,780,7,'Antarctica','Early Jurassic','Cryolophosaurus was a bipedal carnivorous theropod and one of the earliest large meat-eating dinosaurs. Its most distinctive feature was a transverse crest on its skull, giving it a “pompadour” like appearance.','Terrestrial',0,'43.avif'),
(44, 'Acanthopholis', 'horridus','Nomen Dubium',1867,5.5,380,4,'England','Early Cretaceous','Acanthopholis was a small-to-medium-sized herbivorous dinosaur. Its body was covered with oval, keeled armor plates embedded almost horizontally in its skin, and long spikes protruded from the neck and shoulder region and along the spine','Terrestrial',0,'44.avif'),
(45, 'Titanosaurus','blanfordi','Nomen Dubium',1879,18,13000,5,'India','Late Cretaceous','A large, herbivorous sauropod dinosaur from the Late Cretaceous in India. Known only from partial vertebrae, it likely had a long neck and tail, massive body, and walked on four legs, but precise details of its appearance are uncertain.','Avian',0,'45.avif'),
(46, 'Pteranodon','gigas','Nomen Nudum',1975,NULL,NULL,1,'USA','Late Cretaceous','Pteranodon gigas is the largest known Pteranodon ever discovered. It is commonly disputed between paleontologists if Pteranodon gigas are actually misidentified Quetzalcoatlus fossils','Avian',0,'placeholder.avif'),
(47, 'Parasuchus', 'hislopi','Nomen Dubium',1885,3,NULL,5,'India','Late Triassic','A crocodile like, semi aquatic archosaur. It had a long narrow snout, conical teeth, and likely ate fish and small animals','Aquatic',0,'placeholder.avif'),
(48, 'Limulus', 'polyphemus','Valid',1758,0.6,5,1,'Mexico','Early Triassic','Horseshoe crabs are ancient marine arthropods. Their body is covered by a hard, horseshoe shaped carapace, and they have a long tail spine (telson) used for righting themselves if flipped over','Aquatic',1,'placeholder.avif'),
(49, 'Styxosaurus' ,'snowii','Nomen Nudum',1943,12,2300,1,'USA','Late Cretaceous','Styxosaurus was a long necked marine plesiosaur with a small head, a streamlined body and four large paddle like flippers. Its long neck allowed it to stealthily approach fish, squid, and other marine prey; it had conical, sharp teeth suited for grabbing slippery prey and swallowed prey whole.','Aquatic',0,'49.avif'),
(50,'Ichthyosaurus', 'communis','Valid',1818,3,200,4,'Switzerland','Late Triassic','Ichthyosaurus was a dolphin like marine reptile. It had a streamlined body, long narrow snout, paddle-like limbs, and a fish like tail fin. It had large eyes indicating good vision. It had many teeth meant for catching fish and cephalopods.','Aquatic',0,'50.avif'),
(51, 'Aerotitan' ,'sudamericanus','Nomen Nudum',2012,5.8,13,2,'Argentina','Late Cretaceous','Aerotitan sudamericanus was a large pterosaur — part of the flying reptiles — that lived in the Late Cretaceous of what is now Patagonia, Argentina.','Avian',0,'placeholder.avif');

/* 
Valid = A valid and recognised species.
Nomen Nudum = A species that does not have enough evidence to have a name that describes it or to justify it's existence. The name is a placeholder.
Nomen Dubium = A species that will have its name changed soon.

CONTINENTS KEY
 1 => "North America"
 2 => "South America"
 3 => "Africa"
 4 => "Europe"
 5 => "Asia"
 6 => "Austrilia"
 7 => "Antarctica"

WORKFLOW
-git checkout main
 -git status
 -git pull (make sure its up to date)
 -git checkout >your-branch<
 -git pull origin main

 FINISHING TASK (when in your branch)
 -git add .
 -git commit -m
 -git push origin >your-branch<

 -git checkout main
 -git pull origin >your-branch<
*/

CREATE TABLE favourites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    dino_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES group_1_catalogue_admin(account_id),
    FOREIGN KEY (dino_id) REFERENCES dinobase(id)
);

CREATE TABLE profile_pics (
    pic_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pic_url VARCHAR(200) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES group_1_catalogue_admin(account_id)
);