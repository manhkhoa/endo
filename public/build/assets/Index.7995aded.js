import{m as C,A as P,y as T,s as $,r as t,o as r,h as _,j as e,d as a,e as p,c as w,z as B,v as u,x as c,F as j}from"./app.ca5d1c04.js";const H={name:"BackupList"},x=Object.assign(H,{setup(L){C();const m=P("emitter"),f="utility/backup/",d=T(!1),s=$({}),g=o=>{Object.assign(s,o)};return(o,i)=>{const k=t("PageHeaderAction"),b=t("PageHeader"),l=t("DataCell"),y=t("FloatingMenuItem"),v=t("FloatingMenu"),F=t("DataRow"),I=t("DataTable"),D=t("ParentTransition"),h=t("ListItem");return r(),_(h,{"init-url":f,onSetItems:g},{header:e(()=>[a(b,{title:o.$trans("utility.backup.backup")},{default:e(()=>[a(k,{url:"utility/backups/",name:"UtilityBackup",title:o.$trans("utility.backup.backup"),actions:[],"dropdown-actions":["print","pdf","excel"],onToggleFilter:i[0]||(i[0]=n=>d.value=!d.value)},null,8,["title"])]),_:1},8,["title"])]),default:e(()=>[a(D,{appear:"",visibility:!0},{default:e(()=>[a(I,{header:s.headers,meta:s.meta,module:"utility.backup",onRefresh:i[1]||(i[1]=n=>p(m).emit("listItems"))},{default:e(()=>[(r(!0),w(j,null,B(s.data,n=>(r(),_(F,{key:n.uuid},{default:e(()=>[a(l,{name:"name"},{default:e(()=>[u(c(n.name),1)]),_:2},1024),a(l,{name:"size"},{default:e(()=>[u(c(n.size),1)]),_:2},1024),a(l,{name:"action"},{default:e(()=>[a(v,null,{default:e(()=>[a(y,{icon:"fas fa-trash",onClick:M=>p(m).emit("deleteItem",{uuid:n.name})},{default:e(()=>[u(c(o.$trans("general.delete")),1)]),_:2},1032,["onClick"])]),_:2},1024)]),_:2},1024)]),_:2},1024))),128))]),_:1},8,["header","meta"])]),_:1})]),_:1})}}});export{x as default};
