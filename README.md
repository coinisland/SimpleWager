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
The house can call "calimHouseCut" to withdraw house cut.

Events:

- betPlaced(betLeft, amount): someone placed a bet on left (or right) with amount
- wagerEnded(leftWon): the house ended and settled the wager
- winningClaimed(amount): someone claimed his or her winnings
- houseCutClaimed(amount): the house claimed house cut
