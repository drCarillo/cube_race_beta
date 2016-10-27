# cube_race_beta
Go room (cube) to room to finish first

SETUP:
To set up in webserver:
Put /cube_race_beta folder/directory in your webroot: htdocs or www etc.

Then set up data base in MySQL:
1. Create database named: cube_game
2. Import tables with file: /cube_race_beta/sql/build_cube_game_tables
3. PDO drivers for PHP must be enabled as I used that class for my Storage class.

Game world layout:

Start in cube/room 11 which is transparent:
There are rooms in each direction command: north, south, east, west, up, down.
To easily win:
submit 'east'
then
submit 'up'

There are 12 cubes/rooms to this game world.
I called it CubeRace since 'Dungeon' is used so much: hope you don't mind.
Currently there are 3 transparent room with room of cube_id 12 as the winning room
to end the race/game.

There is a monster in cubes 1 and nine.
There are test_players in all solid rooms (9) to speak to.

I did not implement or build a websocket or push server
to have real push messages to all players. So messages
are simulated to web browser. But the design is setup
to use a persistent connection gor the say-tell-yell commands.

I also left out any real validation or scrubbing of data
other than using prepared statements for the db PDO insertions
for this exercise.

Time limits also made the UI very basic with no CSS.

Solid rooms can be exited through entrance otherwse you get a
can't pass through wall message.
Transparent rooms have attached cubes in all directions but you
can limit this by not having cube ids on any wall, ceiling, floor.

Things that need to be designed and implemented: wanna haves but were not included:
a. Websocket and or push server for messages.
b. More elegant UI design.
c. Algorithm to build multiple game worlds: although db design has levels and worlds
   to make possible.
d. Limit to players and monsters in any room/cube, game, and or world.
e. Login system for players.

Possible Issues:
Multiple players:
Rooms get to full where a player could get bomanrded my messages.
Cube/rooms then also could get to busy and interfere with game play:
not to mention may have performance issues further making game play
less fun. There could also be db locks for writes and other performance issues.

Monsters:
Multiple monsters may make lay too intensive although some players
crave such. But there are those same performance issues as well as storage
issues. Plus, you have to create more individual monster abilities which appeal
to players without sacrificing performance: tough call.
