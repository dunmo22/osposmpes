// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
Cypress.Commands.add("login", (username, password) => {
    cy.visit('/')
        .get('input[name="csrf_ospos_v3"]')
        .then(csrfInput => {
            cy.request({
                method: 'POST',
                url: '/login',
                form: true,
                body: {
                    username: username,
                    password: password,
                    csrf_ospos_v3: csrfInput.val()
                }
            })
        })
});

Cypress.Commands.add('enableInvoice', {prevSubject: true}, () => {
    cy.get('meta[name="csrf-token"]').then($csrfMeta => {
        cy.request({
            method: 'POST',
            url: '/config/save_invoice/',
            form: true,
            body: {
                csrf_ospos_v3: $csrfMeta.attr('content'),
                invoice_enable: 'invoice_enable',
                invoice_type: 'invoice',
                default_register_mode: 'sale',
                recv_invoice_format: '{CO}',
                invoice_default_comments: 'This is a default comment',
                invoice_email_message: 'Dear {CU}, In attachment the receipt for sale {ISEQ}',
                line_sequence: '0',
                sales_invoice_format: '{CO}',
                last_used_invoice_number: '3',
                sales_quote_format: 'Q%y{QSEQ:6}',
                last_used_quote_number: '3',
                quote_default_comments: 'This is a default quote comment',
                work_order_format: 'W%y{WSEQ:6}',
                last_used_work_order_number: '0',
                submit_invoice: 'Submit',
            }
        })
    });
});

//
//
// -- This is a child command --
// Cypress.Commands.add("drag", { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add("dismiss", { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite("visit", (originalFn, url, options) => { ... })
