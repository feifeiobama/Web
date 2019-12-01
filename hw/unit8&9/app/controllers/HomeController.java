package controllers;

import models.Guest;
import play.data.Form;
import play.data.FormFactory;
import play.i18n.MessagesApi;
import play.libs.concurrent.HttpExecutionContext;
import play.mvc.Controller;
import play.mvc.Http;
import play.mvc.Result;
import play.mvc.Results;
import repository.GuestRepository;

import javax.inject.Inject;
import javax.persistence.PersistenceException;
import java.util.Map;
import java.util.concurrent.CompletionStage;

import static java.util.concurrent.CompletableFuture.supplyAsync;

/**
 * Manage a database of guests
 */
public class HomeController extends Controller {

    private final GuestRepository guestRepository;
    private final FormFactory formFactory;
    private final HttpExecutionContext httpExecutionContext;
    private final MessagesApi messagesApi;

    @Inject
    public HomeController(FormFactory formFactory,
                          GuestRepository guestRepository,
                          HttpExecutionContext httpExecutionContext,
                          MessagesApi messagesApi) {
        this.guestRepository = guestRepository;
        this.formFactory = formFactory;
        this.httpExecutionContext = httpExecutionContext;
        this.messagesApi = messagesApi;
    }

    /**
     * This result directly redirect to application home.
     */
    private Result GO_HOME = Results.redirect(
        routes.HomeController.list(0, "guest_id", "asc", "")
    );

    /**
     * Handle default path requests, redirect to guests list
     */
    public Result index() {
        return GO_HOME;
    }

    /**
     * Display the paginated list of guests.
     *
     * @param page   Current page number (starts from 0)
     * @param sortBy Column to be sorted
     * @param order  Sort order (either asc or desc)
     * @param filter Filter applied on guest names
     */
    public CompletionStage<Result> list(Http.Request request, int page, String sortBy, String order, String filter) {
        // Run a db operation in another thread (using DatabaseExecutionContext)
        return guestRepository.page(page, 10, sortBy, order, filter).thenApplyAsync(list -> {
            // This is the HTTP rendering thread context
            return ok(views.html.list.render(list, sortBy, order, filter, request, messagesApi.preferred(request)));
        }, httpExecutionContext.current());
    }

    /**
     * Display the 'edit form' of a existing guest.
     *
     * @param id Id of the guest to edit
     */
    public CompletionStage<Result> edit(Http.Request request,Long id) {

        // Run the lookup also in another thread, then combine the results:
        return guestRepository.lookup(id).thenApplyAsync(guestOptional -> {
            // This is the HTTP rendering thread context
            Guest c = guestOptional.get();
            Form<Guest> guestForm = formFactory.form(Guest.class).fill(c);
            return ok(views.html.editForm.render(id, guestForm, request, messagesApi.preferred(request)));
        }, httpExecutionContext.current());
    }

    /**
     * Handle the 'edit form' submission
     *
     * @param id Id of the guest to edit
     */
    public CompletionStage<Result> update(Http.Request request, Long id) throws PersistenceException {
        Form<Guest> guestForm = formFactory.form(Guest.class).bindFromRequest(request);
        if (guestForm.hasErrors()) {
            return supplyAsync(() -> {
               return badRequest(views.html.editForm.render(id, guestForm, request, messagesApi.preferred(request)));
           }, httpExecutionContext.current());
        } else {
            Guest newGuestData = guestForm.get();
            // Run update operation and then flash and then redirect
            return guestRepository.update(id, newGuestData).thenApplyAsync(data -> {
                // This is the HTTP rendering thread context
                return GO_HOME.flashing("success", "data from " + newGuestData.guest_name + " has been updated");
            }, httpExecutionContext.current());
        }
    }

    /**
     * Display the 'new guest form'.
     */
    public CompletionStage<Result> create(Http.Request request) {
        Form<Guest> guestForm = formFactory.form(Guest.class);
        // Run companies db operation and then render the form
        return supplyAsync(() -> {
            // This is the HTTP rendering thread context
            return ok(views.html.createForm.render(guestForm, request, messagesApi.preferred(request)));
        }, httpExecutionContext.current());
    }

    /**
     * Handle the 'new guest form' submission
     */
    public CompletionStage<Result> save(Http.Request request) {
        Form<Guest> guestForm = formFactory.form(Guest.class).bindFromRequest(request);
        if (guestForm.hasErrors()) {
            return supplyAsync(() -> {
               return badRequest(views.html.createForm.render(guestForm, request, messagesApi.preferred(request)));
           }, httpExecutionContext.current());
        } else {
            Guest guest = guestForm.get();
            // Run insert db operation, then redirect
            return guestRepository.insert(guest).thenApplyAsync(data -> {
                // This is the HTTP rendering thread context
                return GO_HOME.flashing("success", guest.guest_name + " has joined survey");
            }, httpExecutionContext.current());
        }
    }

    /**
     * Handle guest deletion
     */
    public CompletionStage<Result> delete(Long id) {
        // Run delete db operation, then redirect
        return guestRepository.delete(id).thenApplyAsync(v -> {
            // This is the HTTP rendering thread context
            return GO_HOME.flashing("success", "data has been deleted");
        }, httpExecutionContext.current());
    }

}
            
