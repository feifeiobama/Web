#!/usr/bin/perl -w
# delete.pl
# This CGI program processes the consumer electronics survey
#  form and updates the file that stores the survey

use CGI ":standard";

# error - a function to produce an error message for the client
#         and exit in case of open errors

sub error {
    print "Error file could not be opened in delete.pl <br/>";
    print end_html();
    exit(1);
}

# Begin main program
# Get the form values

if (param("ids[]")) {
	@ids = param("ids[]");
}else {
	@ids = (param("ids"));
}

# Produce the header for the reply page - do it here so
#  error messages appear on the page

print header(
    -charset=>"UTF-8",
);

# Set names for file locking and unlocking

$LOCK = 2;
$UNLOCK = 8;

# Open and lock the survey data file

open(SURVDAT, "<survdat.dat") or error();
flock(SURVDAT, $LOCK);

# Read the survey data file, unlock it, and close it

@data = <SURVDAT>;

flock(SURVDAT, $UNLOCK);
close(SURVDAT);

# delete selected items

@newdata = ();
for ($i = 0, $j = 0; $i < scalar @data; $i++) {
	if ($ids[$j] - 1 != $i) {
		push @newdata, $data[$i];
		if ($ids[$j] - 1 < $i && $j < scalar @ids - 1) {
			$j++;
		}
	} elsif ($j < scalar @ids - 1) {
		$j++;
	}
}

# Reopen the survey data file for writing and lock it

open(SURVDAT, ">survdat.dat") or error();
flock(SURVDAT, $LOCK);

# Write out the file data, unlock the file, and close it

foreach (@newdata) {
	print SURVDAT $_;
}

flock(SURVDAT, $UNLOCK);
close(SURVDAT);

# Build the Web page to thank the survey participant
print start_html("Deleted");
print scalar @ids, " item(s) selected, ", scalar @data - scalar @newdata, " item(s) deleted.";
print '<br></br>[<a href="list.pl">Return</a>]';
print end_html();

