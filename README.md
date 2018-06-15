# SimpleWager
A simple Ethereum smart contract for creating and placing bets.

To create a wager:

Deploy this contract with the following parameters:

- wagerName: name of this wager, such as "Will German win World Cup 2018?"
- leftBetName: name of the left side, such as "Yes"
- rightBetName: name of the right side, such as "No"
- activeTime: active time of the wager in seconds starting from now. e.g. 3600 for 1 hour
- minBet: minimum amount to place a bet, in wei
- houseCut: house cut in percentage (e.g. 5 for 5%)

Once the contract is deployed, anyone can call "placeBet" function to place their bet.
e.g. 

- placeBet(true) to bet the left side, or
- placeBet(false) to bet the right side.

The amount of the bet is sent along with the function call.

After the wager ended, the house (the original address who deployed the contract) calls "endWager" to settle the bet.
e.g.

- endWager(true) to end the wager and announce that the left side has won, or
- endWager(false) to end the wager and announce that the right side has won.

After endWager is called, winners are able to call "claim" function to get their winnings back to their account.
The house can call "claimHouseCut" to withdraw house cut.

Events:

- BetPlaced(betLeft, amount): someone placed a bet on left (or right) with amount
- WagerEnded(leftWon): the house ended and settled the wager
- WinningClaimed(amount): someone claimed his or her winnings
- HouseCutClaimed(amount): the house claimed house cut

# Web Front End
bet.php and house.php for working with Metamask.
These are just a simple frontend with no need for a backend server (only require a server for serving the files as Metamask does not allow using local HTML files).

Usage:

- The house uses house.php to deploy a wager.
- After a wager is successfully deployed, it produces a link to bet.php with the contract address, and anyone (other than the house) can place bets using the link.
- After the wager is ended, the house use the same link to declare a winner. Then everyone who won the bet can claim their winnings. The house can claim the house cut.

Both bet.php and house.php has a variable called 'supportedNetworkType' to designate which network to use. By default, it uses the Ropsten test network.

A test front end using the Ropsten test network is running at https://clover.kimicat.com/house.php and https://clover.kimicat.com/bet.php.
