#!/usr/bin/perl -w
# submit.pl
# This CGI program processes the consumer electronics survey
#  form and updates the file that stores the survey

use CGI ":standard";

# error - a function to produce an error message for the client
#         and exit in case of open errors

sub error {
    print "Error file could not be opened in submit.pl <br/>";
    print end_html();
    exit(1);
}

# Begin main program
# Get the form values

my($name, $age, $gender, $email) = (param("name"), param("age"), param("gender"), param("email"));

# Produce the header for the reply page - do it here so
#  error messages appear on the page

print header(
    -charset=>"UTF-8",
);

# Set names for file locking and unlocking

$LOCK = 2;
$UNLOCK = 8;

#open the survey data file for writing and lock it

open(SURVDAT, ">>survdat.dat") or error();
flock(SURVDAT, $LOCK);

# Write out the file data, unlock the file, and close it

# remove spaces

sub filter {
	my($val) = @_;
	$val =~ s/</&lt;/g;
	$val =~ s/>/&gt;/g;
	return $val;
}

printf SURVDAT "%s,%s,%s,%s\n", filter($name), filter($age), filter($gender), filter($email);

flock(SURVDAT, $UNLOCK);
close(SURVDAT);

# Build the Web page to thank the survey participant
print start_html("Thanks");
print "Thanks for participating in our questionnaire.";
print '<br></br>[<a href="../hw/unit3/index.html">Return</a>]';
print end_html();

