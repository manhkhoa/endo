import{k as P,s as B,t as R,r as o,o as m,h as f,j as e,a as F,d as t,i as k,m as M,A as j,p as b,y as N,e as l,v as c,x as d,c as U,z as q,n as z,F as E}from"./app.ca5d1c04.js";const O={class:"grid grid-cols-3 gap-6"},G={class:"col-span-3 sm:col-span-1"},J={class:"col-span-3 sm:col-span-1"},K={class:"col-span-3 sm:col-span-1"},Q={__name:"Filter",emits:["hide"],setup(V,{emit:_}){const g=P(),$={name:"",alias:"",ledgerTypes:[]},i=B({...$}),y=B({ledgerTypes:[],isLoaded:!g.query.ledgerTypes});return R(async()=>{y.ledgerTypes=g.query.ledgerTypes?g.query.ledgerTypes.split(","):[],y.isLoaded=!0}),(u,n)=>{const C=o("BaseInput"),s=o("BaseSelectSearch"),r=o("FilterForm");return m(),f(r,{"init-form":$,form:i,multiple:["ledgerTypes"],onHide:n[3]||(n[3]=p=>_("hide"))},{default:e(()=>[F("div",O,[F("div",G,[t(C,{type:"text",modelValue:i.name,"onUpdate:modelValue":n[0]||(n[0]=p=>i.name=p),name:"name",label:u.$trans("finance.ledger.props.name")},null,8,["modelValue","label"])]),F("div",J,[t(C,{type:"text",modelValue:i.alias,"onUpdate:modelValue":n[1]||(n[1]=p=>i.alias=p),name:"alias",label:u.$trans("finance.ledger.props.alias")},null,8,["modelValue","label"])]),F("div",K,[y.isLoaded?(m(),f(s,{key:0,multiple:"",name:"ledgerTypes",label:u.$trans("global.select",{attribute:u.$trans("finance.ledger_type.ledger_type")}),modelValue:i.ledgerTypes,"onUpdate:modelValue":n[2]||(n[2]=p=>i.ledgerTypes=p),"label-prop":"name","value-prop":"uuid","init-search":y.ledgerTypes,"search-key":"name","search-action":"finance/ledgerType/list"},null,8,["label","modelValue","init-search"])):k("",!0)])])]),_:1},8,["form"])}}},W={name:"FinanceLedgerList"},Y=Object.assign(W,{setup(V){const _=M(),g=j("emitter");let $=["filter"];b("ledger:create")&&$.unshift("create");let i=[];b("ledger:export")&&(i=["print","pdf","excel"]);const y="finance/ledger/",u=N(!1),n=B({}),C=s=>{Object.assign(n,s)};return(s,r)=>{const p=o("PageHeaderAction"),I=o("PageHeader"),L=o("ParentTransition"),v=o("DataCell"),T=o("FloatingMenuItem"),w=o("FloatingMenu"),D=o("DataRow"),A=o("BaseButton"),S=o("DataTable"),H=o("ListItem");return m(),f(H,{"init-url":y,onSetItems:C},{header:e(()=>[t(I,{title:s.$trans("finance.ledger.ledger"),navs:[{label:s.$trans("finance.finance"),path:"Finance"}]},{default:e(()=>[t(p,{url:"finance/ledgers/",name:"FinanceLedger",title:s.$trans("finance.ledger.ledger"),actions:l($),"dropdown-actions":l(i),onToggleFilter:r[0]||(r[0]=a=>u.value=!u.value)},null,8,["title","actions","dropdown-actions"])]),_:1},8,["title","navs"])]),filter:e(()=>[t(L,{appear:"",visibility:u.value},{default:e(()=>[t(Q,{onRefresh:r[1]||(r[1]=a=>l(g).emit("listItems")),onHide:r[2]||(r[2]=a=>u.value=!1)})]),_:1},8,["visibility"])]),default:e(()=>[t(L,{appear:"",visibility:!0},{default:e(()=>[t(S,{header:n.headers,meta:n.meta,module:"finance.ledger",onRefresh:r[4]||(r[4]=a=>l(g).emit("listItems"))},{actionButton:e(()=>[l(b)("ledger:create")?(m(),f(A,{key:0,onClick:r[3]||(r[3]=a=>l(_).push({name:"FinanceLedgerCreate"}))},{default:e(()=>[c(d(s.$trans("global.add",{attribute:s.$trans("finance.ledger.ledger")})),1)]),_:1})):k("",!0)]),default:e(()=>[(m(!0),U(E,null,q(n.data,a=>(m(),f(D,{key:a.uuid},{default:e(()=>[t(v,{name:"name"},{default:e(()=>[c(d(a.name),1)]),_:2},1024),t(v,{name:"alias"},{default:e(()=>[c(d(a.alias),1)]),_:2},1024),t(v,{name:"type"},{default:e(()=>[c(d(a.type.name),1)]),_:2},1024),t(v,{name:"balance"},{default:e(()=>[F("span",{class:z({"font-semibold":!0,"text-success":a.balanceColor=="success","text-danger":a.balanceColor=="danger"})},d(a.balanceDisplay),3)]),_:2},1024),t(v,{name:"createdAt"},{default:e(()=>[c(d(a.createdAt),1)]),_:2},1024),t(v,{name:"action"},{default:e(()=>[t(w,null,{default:e(()=>[t(T,{icon:"fas fa-arrow-circle-right",onClick:h=>l(_).push({name:"FinanceLedgerShow",params:{uuid:a.uuid}})},{default:e(()=>[c(d(s.$trans("general.show")),1)]),_:2},1032,["onClick"]),l(b)("ledger:edit")?(m(),f(T,{key:0,icon:"fas fa-edit",onClick:h=>l(_).push({name:"FinanceLedgerEdit",params:{uuid:a.uuid}})},{default:e(()=>[c(d(s.$trans("general.edit")),1)]),_:2},1032,["onClick"])):k("",!0),l(b)("ledger:create")?(m(),f(T,{key:1,icon:"fas fa-copy",onClick:h=>l(_).push({name:"FinanceLedgerDuplicate",params:{uuid:a.uuid}})},{default:e(()=>[c(d(s.$trans("general.duplicate")),1)]),_:2},1032,["onClick"])):k("",!0),l(b)("ledger:delete")?(m(),f(T,{key:2,icon:"fas fa-trash",onClick:h=>l(g).emit("deleteItem",{uuid:a.uuid})},{default:e(()=>[c(d(s.$trans("general.delete")),1)]),_:2},1032,["onClick"])):k("",!0)]),_:2},1024)]),_:2},1024)]),_:2},1024))),128))]),_:1},8,["header","meta"])]),_:1})]),_:1})}}});export{Y as default};