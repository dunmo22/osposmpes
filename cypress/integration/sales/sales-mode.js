context('Invoice enabled', () => {
    before(() => {
        cy.login('admin', 'pointofsale')
            .enableInvoice();
    });

    beforeEach(() => {

    });

    describe('Selecting sales-mode', () => {
        it('', function () {
            // cy.visit('http://ospos.local/sales')
        });
    })
});
