#!/usr/bin/perl -w
# list.pl - display the survey results

use CGI ":standard";

# error - a function to produce an error message for the client
#         and exit in case of open errors

sub error {
    print "Error - file could not be opened in list.pl <br/>";
    print end_html();
    exit(1);
}

# Begin main program
# Initialize file locking/unlocking parameter

$LOCK = 2;
$UNLOCK = 8;

print header(
    -charset=>"UTF-8",
);

# Open, lock, read, and unlock the survey data file

open(SURVDAT, "<survdat.dat") or error();
flock(SURVDAT, $LOCK);
@data = <SURVDAT>;
flock(SURVDAT, $UNLOCK);
close(SURVDAT);

# Create the beginning of the result Web page

print start_html(
    -title=>"Results",
    -style=>{src=>'../css/index.css'},
);
print "<h2>Results of the Basic Information Questionnaire</h2>";

# Make the column titles array

@col_titles = ("Name", "Age", "Gender", "E-mail", "Checkbox");

# Create the column titles in HTML by giving their address to the th
#  function and storing the return value in the @rows array

@rows = th(\@col_titles);

my $index = 0;

foreach (@data) {
    @item = split(/,/);
    my $checkbox = sprintf("<input type='checkbox' value='%d' name='ids'/>", ++$index);
    push @item, $checkbox;
    push(@rows, td(\@item));
}

# Create the table for the female survey results
#  The address of the array of row addresses is passed to Tr

print '<form action="delete.pl" method="post">';
print table(caption("Questionnaire Data"), Tr(\@rows));
print '<br/>';
print scalar @data,' item(s) in total. To delete selected items, click ';
print '<input type="submit" value="this button"/>';
print '.';
print '</form>';
print '<br></br>[<a href="../hw/unit3/index.html">Return Unit 3</a>]&nbsp;&nbsp;[<a href="list.pl">Refresh</a>]';

print end_html();

