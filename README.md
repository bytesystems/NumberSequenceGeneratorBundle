## Pattern
The pattern is can be provided as annotation.
The *{#}* token is mandatory.

The following tokens are available, date components follow php datetime.format.

- *{#|L|C}* The current number of the sequence. 
  If L is provided, the number will be left-padded with zeroes
  If C, the date context, is provided, the number will reset on change of context.
  Allowed values for context are (y = year, m = month, d = day, W = week)
- *{D}* Datetime part following datetime.format, allowed values for D are almost all values that produce a fixed length, f.e. d (day of month, 2 digits with leading zero) is allowed, while j (day of month, without leading zeroes) is not.
  Allowed values:
  - d, day, 2 digits with leading zero
  - D, day, textual 3 letters
  - m, month, 2 digits with leading zero
  - M, month, textual 3 letters
  - y, year, 2 digits
  - Y, year, 4 digits
  - H, hours, 24-hour format, 2 digits with leading zero
  - w, week of year, 2 digits with leading zero (*changed to lowercase for consistency*)