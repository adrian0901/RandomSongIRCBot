<?php
// PROGRAM: bot do IRCa "losowa piosenka" / IRC bot "random song"
// AUTHOR: adrian09_01
// SHORT DESCRIPTION:
// PL: Bot wybiera losową piosenkę (piosenki) z listy utworów po wpisaniu !randomsong <liczba piosenek>
// EN: The bot chooses one or more songs from the list after typing !randomsong <song amount>
// LICENSE: GNU GPL v3
// BASED ON: http://hawkee.com/snippet/5330/ by F*U*R*B*Y

// nie pozwól PHP ubić skryptu po 30 sekundach
// don't let PHP kill the script after 30 seconds
set_time_limit(0);

// dane do połączenia
// connection data
$chan = "#music-bot"; // kanał / channel
$server = "91.217.189.42"; // serwer / server
$port = 6667; //port
$nick = "RandomMusic_BOT"; // ksywka / nick
$ident = "rmbot"; // identyfikator / ident
$gecos = "github.com/adrian0901"; // w teorii miejsce na imię i nazwisko / theoretically meant for real name
$music = array( // lista muzyki / music list
"Luxtorpeda - Autystyczny https://www.youtube.com/watch?v=-hqbkY1-iLQ",
"Luxtorpeda - Hymn https://www.youtube.com/watch?v=jbjbfUlX2Vc",
"Luxtorpeda - Wilki dwa https://www.youtube.com/watch?v=pHyu1yH1kgs",
"Elektryczne Gitary - Dzieci wybiegly https://www.youtube.com/watch?v=q4EM1jSg92k",
"Elektryczne Gitary - Wlosy https://www.youtube.com/watch?v=mNW8pNRtjRU",
"Elektryczne Gitary - Czlowiek z lisciem https://www.youtube.com/watch?v=pt3g8HPwDSc",
"Elektryczne Gitary - To juz jest koniec https://www.youtube.com/watch?v=RuiXUySUw70",
"Elektryczne Gitary - Kiler https://www.youtube.com/watch?v=leYyu4wH4dQ",
"Elektryczne Gitary - Jestem z miasta https://www.youtube.com/watch?v=a_I94dWg0p0",
"Arash ft. Rebecca - Temptation https://www.youtube.com/watch?v=LfdAX8n6UbA",
"Avenue Q - The Internet is for Porn https://www.youtube.com/watch?v=j6eFNRKEROw",
"Lady Pank - Wciaz bardziej obcy https://www.youtube.com/watch?v=PwKs5uH451Y",
"Lady Pank - Zawsze tam gdzie ty https://www.youtube.com/watch?v=fyoCXePXQF0",
"Lady Pank - Marchewkowe pole https://www.youtube.com/watch?v=8unEo4tdVVQ",
"Lady Pank - Mniej niz zero https://www.youtube.com/watch?v=Lc-26SnSmok",
"Lady Pank - Kryzysowa narzeczona https://www.youtube.com/watch?v=SPnEpzUy2V4",
"Lady Pank - Moj swiat bez ciebie https://www.youtube.com/watch?v=U5LLkehUa-c",
"Lady Pank - Mala Wojna https://www.youtube.com/watch?v=Hsp2WE9rWTs",
"Lady Pank - Tacy sami https://www.youtube.com/watch?v=pyewz3w3pnA",
"Lady Pank - Zamki na piasku https://www.youtube.com/watch?v=lGZ1mdkF3Bo",
"Lady Pank - 7-me niebo nienawisci https://www.youtube.com/watch?v=Q1NipppqA4Y",
"Dzem - Wehikul czasu https://www.youtube.com/watch?v=XWcqFbMUAb4",
"Dzem - Czerwony jak cegla https://www.youtube.com/watch?v=eZtRqHildg8",
"Oddzial Zamkniety - Obudz sie https://www.youtube.com/watch?v=b7l6ksWZi-8",
"Oddzial Zamkniety - Ten wasz swiat https://www.youtube.com/watch?v=QxQA6WMgMBg",
"Perfect - Autobiografia https://www.youtube.com/watch?v=AJQ4OVcEiDQ",
"Perfect - Ale w kolo jest wesolo https://www.youtube.com/watch?v=1_XsuuQNT9E",
"Perfect - Chcemy byc soba https://www.youtube.com/watch?v=1qeMxFRD100",
"Perfect - Kolysanka dla nieznajomej https://www.youtube.com/watch?v=qKeYmd7EK9k",
"Budka Suflera - Takie Tango https://www.youtube.com/watch?v=_GZB_nP4uWE",
"Budka Suflera - Jest taki samotny dom https://www.youtube.com/watch?v=wT_ObQMSs3U",
"Budka Suflera - Bal wszystkich swietych https://www.youtube.com/watch?v=kE87oI5rdew",
"Budka Suflera - Sen o dolinie https://www.youtube.com/watch?v=Hslac_tmcNE",
"Budka Suflera - Cisza jak ta https://www.youtube.com/watch?v=OOtLKvsx1Jo",
"Tadeusz Wozniak - Zegarmistrz Swiatla https://www.youtube.com/watch?v=obvizJRnezA",
"Tadeusz Wozniak - Wierze w czlowieka https://www.youtube.com/watch?v=U025GZqLZOg",
"Marek Grechuta - Dni ktorych nie znamy https://www.youtube.com/watch?v=oG6pEolAKm8",
"Marek Grechuta - Nie dokazuj https://www.youtube.com/watch?v=F6J_JROaIxs",
"Marek Grechuta - Bedziesz moja pania https://www.youtube.com/watch?v=rW0TgYZn_tk",
"Marek Grechuta - Wiosna ach to ty https://www.youtube.com/watch?v=KvdNyMgA16E",
"Marek Grechuta - Swiecie nasz https://www.youtube.com/watch?v=fcz7klh-qdI"
);


// otwórz socketa i połącz się z serwerem
// open socket and connect to server
$socket = fsockopen("$server", $port);
// wyślij dane identyfikacyjne
// send ident data
fputs($socket,"USER $ident * 8 :$gecos\r\n");
// zmień nick
// change the nick
fputs($socket,"NICK $nick\n");
// dołącz do kanału
// join the channel
fputs($socket,"JOIN ".$chan."\n");

// pętla
// loop
while(1) {
	// czekaj na dane z IRCa
	// wait for IRC data
    while($data = fgets($socket)) {
        echo nl2br($data);
        flush();

		//parsuj dane z ostatniej wiadomości
		//parse data from last message
        $ex = explode(' ', $data);
        $command = explode(':', $ex[3]);
        $oneword = explode('<br>', $command);
        $channel = $ex[2];
        $nicka = explode('@', $ex[0]);
        $nickb = explode('!', $nicka[0]);
        $nickc = explode(':', $nickb[0]);

        $host = $nicka[1];
        $nick = $nickc[1];
		
		//przetwarzanie pingów - bez tego bot zostanie wyrzucony z serwera
		//ping handling - without it the bot will be kicked
        if($ex[0] == "PING"){
            fputs($socket, "PONG ".$ex[1]."\n");
        }
		
		//pobierz argumenty do komendy
		//get command arguments
        $args = NULL; for ($i = 4; $i < count($ex); $i++) { $args .= $ex[$i] . ' '; }

		//reaguj na komendę !randomsong wysyłając adres YouTube piosenki(piosenek) z listy
		//jeżeli mniej jak 5 piosenek (żeby nie było spamu)
		//jeżeli więcej wyślij wiadomość informującą o limicie
		
		//react to the !randomsong command by sending an YouTube address of a song(songs) from the list
		//if less than 5 songs (to prevent spam)
		//else send a message about the limit
        if ($command[1] == "!randomsong") {
			if ($args <= 5) { 
				for ($x = 1; $x <= $args; $x++) {
					fputs($socket, "PRIVMSG ".$channel." :"."Got it! Your random song #".$x." is ".$music[array_rand($music)]." \n");
				}
			}
			else {
				fputs($socket, "PRIVMSG ".$channel." :"."I can only supply 5 random songs at a time to you. \n");
			}
        }
    }
}
?>