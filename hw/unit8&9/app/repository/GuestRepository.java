package repository;

import io.ebean.*;
import models.Guest;
import play.db.ebean.EbeanConfig;

import javax.inject.Inject;
import java.util.Optional;
import java.util.concurrent.CompletionStage;

import static java.util.concurrent.CompletableFuture.supplyAsync;

/**
 * A repository that executes database operations in a different
 * execution context.
 */
public class GuestRepository {

    private final EbeanServer ebeanServer;
    private final DatabaseExecutionContext executionContext;

    @Inject
    public GuestRepository(EbeanConfig ebeanConfig, DatabaseExecutionContext executionContext) {
        this.ebeanServer = Ebean.getServer(ebeanConfig.defaultServer());
        this.executionContext = executionContext;
    }

    /**
     * Return a paged list of guest
     *
     * @param page     Page to display
     * @param pageSize Number of guests per page
     * @param sortBy   guest property used for sorting
     * @param order    Sort order (either or asc or desc)
     * @param filter   Filter applied on the name column
     */
    public CompletionStage<PagedList<Guest>> page(int page, int pageSize, String sortBy, String order, String filter) {
        return supplyAsync(() ->
            ebeanServer.find(Guest.class)
                .where().ilike("guest_name", "%" + N(filter) + "%")
                .orderBy(sortBy + " " + order)
                .setFirstRow(page * pageSize)
                .setMaxRows(pageSize)
                .findPagedList()
        , executionContext);
    }

    public CompletionStage<Optional<Guest>> lookup(Long id) {
        return supplyAsync(() -> Optional.ofNullable(ebeanServer.find(Guest.class).setId(id).findOne()), executionContext);
    }

    public CompletionStage<Optional<Long>> update(Long id, Guest newGuestData) {
        return supplyAsync(() -> {
            Transaction txn = ebeanServer.beginTransaction();
            Optional<Long> value = Optional.empty();
            try {
                Guest savedGuest = ebeanServer.find(Guest.class).setId(id).findOne();
                if (savedGuest != null) {
                    savedGuest.guest_name = newGuestData.guest_name;
                    savedGuest.age = newGuestData.age;
                    savedGuest.gender = newGuestData.gender;
                    savedGuest.e_mail = newGuestData.e_mail;

                    savedGuest.update();
                    txn.commit();
                    value = Optional.of(id);
                }
            } finally {
                txn.end();
            }
            return value;
        }, executionContext);
    }

    public CompletionStage<Optional<Long>>  delete(Long id) {
        return supplyAsync(() -> {
            try {
                final Optional<Guest> guestOptional = Optional.ofNullable(ebeanServer.find(Guest.class).setId(id).findOne());
                guestOptional.ifPresent(Model::delete);
                return guestOptional.map(c -> c.guest_id);
            } catch (Exception e) {
                return Optional.empty();
            }
        }, executionContext);
    }

    public CompletionStage<Long> insert(Guest guest) {
        return supplyAsync(() -> {
//              guest.id = System.currentTimeMillis(); // not ideal, but it works
             ebeanServer.insert(guest);
             return guest.guest_id;
        }, executionContext);
    }
}
